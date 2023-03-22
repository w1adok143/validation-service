<?php

namespace Kronas\Api\Customer\Services\Dsp;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Kronas\Api\BaseApiController;
use Kronas\Api\BaseApiException;
use Kronas\Lib\Detail\Detail;
use Kronas\Lib\Material\Material;
use Kronas\Lib\Validator\Validator;

class DspController extends BaseApiController
{
    /**
     * Доступні операції
     */
    private array $handlers = ['edges', 'holes', 'grooves', 'quarters', 'cutouts', 'corners', 'arcs', 'smiles'];

    /**
     * Ініціалізація
     *
     * @param Validator $validator
     * @param DspModel $model
     */
    public function __construct(
        private Validator $validator,
        private DspModel $model
    ) {}

    /**
     * Валідація операцій
     *
     * @param Request $request
     * @return JsonResponse
     * @throws BaseApiException
     */
    public function verify(Request $request): JsonResponse
    {
        $this->validator->validate($request, $this->rules());

        try {
            $this->checkDepartment($request);

            foreach ($request->details as $current => $row) {
                try {
                    $handlers = array_keys($row['handlers']);
                    $handlers = array_diff($handlers, $this->handlers);
                    $handlers = array_values($handlers);

                    if (!empty($handlers)) {
                        throw new DspHandlerException(['handler__forbidden', $handlers[0]]);
                    }

                    $handler = $this->createHandler($row);

                    if (!empty($row['handlers']['edges'])) {
                        $handler->verifyEdges($handler->createEdges($row['handlers']['edges']));
                    }
                    if (!empty($row['handlers']['holes'])) {
                        $handler->verifyHoles($handler->createHoles($row['handlers']['holes']));
                    }
                    if (!empty($row['handlers']['grooves'])) {
                        $handler->verifyGrooves($handler->createGrooves($row['handlers']['grooves']));
                    }
                    if (!empty($row['handlers']['quarters'])) {
                        $handler->verifyQuarters($handler->createQuarters($row['handlers']['quarters']));
                    }
                    if (!empty($row['handlers']['cutouts'])) {
                        $handler->verifyCutouts($handler->createCutouts($row['handlers']['cutouts']));
                    }
                    if (!empty($row['handlers']['corners'])) {
                        $handler->verifyCorners($handler->createCorners($row['handlers']['corners']));
                    }
                } catch (DspHandlerException $e) {
                    $e->setErrorDetailIndex($current);

                    throw $e;
                }
            }

            return success();
        } catch (DspHandlerException $e) {
            return errorDspHandler($e);
        }
    }

    /**
     * Перевірити філіал
     *
     * @param Request $request
     * @return void
     * @throws DspHandlerException
     */
    private function checkDepartment(Request $request): void
    {
        /** Ідентифікатор філіалу */
        $id = $request->department;
        /** Деталі */
        $details = $request->details;
        /** Філіал */
        $department = $this->model->select($id);

        if (empty($department)) {
            throw new DspHandlerException(['department__not_found', [$id]]);
        }
        if (empty($department['settings']['min_length'])) {
            throw new DspHandlerException(['department__detail__min_length__not_found', [$id]]);
        }
        if (empty($department['settings']['max_length'])) {
            throw new DspHandlerException(['department__detail__max_length__not_found', [$id]]);
        }
        if (empty($department['settings']['min_width'])) {
            throw new DspHandlerException(['department__detail__min_width__not_found', [$id]]);
        }
        if (empty($department['settings']['max_width'])) {
            throw new DspHandlerException(['department__detail__max_width__not_found', [$id]]);
        }

        foreach ($details as $current => $row) {
            $minLength = $department['settings']['min_length'];
            $maxLength = $department['settings']['max_length'];

            $minWidth = $department['settings']['min_width'];
            $maxWidth = $department['settings']['max_width'];

            try {
                if ($minLength > $row['height']) {
                    throw new DspHandlerException(['department__detail__min_length', [$id, $minLength]]);
                }
                if ($maxLength < $row['height']) {
                    throw new DspHandlerException(['department__detail__max_length', [$id, $maxLength]]);
                }
                if ($minWidth > $row['width']) {
                    throw new DspHandlerException(['department__detail__min_width', [$id, $minWidth]]);
                }
                if ($maxWidth < $row['width']) {
                    throw new DspHandlerException(['department__detail__max_width', [$id, $maxWidth]]);
                }
            } catch (DspHandlerException $e) {
                $e->setErrorDetailIndex($current);
                $e->setErrorHandler('detail');

                throw $e;
            }
        }
    }

    /**
     * Створити обробник по операціям
     *
     * @param array $row
     * @return DspHandler
     */
    private function createHandler(array $row): DspHandler
    {
        $material = new Material($row['material']['thickness']);
        $detail = new Detail($material, $row['height'], $row['width']);

        return new DspHandler($detail);
    }

    /**
     * Правила валідації
     *
     * @return string[]
     */
    private function rules(): array
    {
        return [
            'department',
            'details',

            'details.*.material',
            'details.*.material.thickness',

            'details.*.height',
            'details.*.width',

            'details.*.handlers',
            'details.*.handlers.edges',

            'details.*.handlers.edges.left',
            'details.*.handlers.edges.left.thickness',
            'details.*.handlers.edges.left.width',

            'details.*.handlers.edges.top',
            'details.*.handlers.edges.top.thickness',
            'details.*.handlers.edges.top.width',

            'details.*.handlers.edges.right',
            'details.*.handlers.edges.right.thickness',
            'details.*.handlers.edges.right.width',

            'details.*.handlers.edges.bottom',
            'details.*.handlers.edges.bottom.thickness',
            'details.*.handlers.edges.bottom.width',

            'details.*.handlers.holes',
            'details.*.handlers.holes.*.subtype',
            'details.*.handlers.holes.*.side',
            'details.*.handlers.holes.*.x',
            'details.*.handlers.holes.*.y',
            'details.*.handlers.holes.*.z',
            'details.*.handlers.holes.*.diam',
            'details.*.handlers.holes.*.depth',

            'details.*.handlers.grooves',
            'details.*.handlers.grooves.*.subtype',
            'details.*.handlers.grooves.*.side',
            'details.*.handlers.grooves.*.direction',
            'details.*.handlers.grooves.*.x',
            'details.*.handlers.grooves.*.y',
            'details.*.handlers.grooves.*.z',
            'details.*.handlers.grooves.*.height',
            'details.*.handlers.grooves.*.width',
            'details.*.handlers.grooves.*.depth',
            'details.*.handlers.grooves.*.fullDepth',
            'details.*.handlers.grooves.*.isFull',
            'details.*.handlers.grooves.*.r',
            'details.*.handlers.grooves.*.ext',
            'details.*.handlers.grooves.*.edge',
            'details.*.handlers.grooves.*.edge.thickness',
            'details.*.handlers.grooves.*.edge.width',

            'details.*.handlers.quarters',
            'details.*.handlers.quarters.*.subtype',
            'details.*.handlers.quarters.*.subside',
            'details.*.handlers.quarters.*.side',
            'details.*.handlers.quarters.*.x',
            'details.*.handlers.quarters.*.y',
            'details.*.handlers.quarters.*.height',
            'details.*.handlers.quarters.*.width',
            'details.*.handlers.quarters.*.depth',
            'details.*.handlers.quarters.*.fullDepth',
            'details.*.handlers.quarters.*.isFull',
            'details.*.handlers.quarters.*.r',
            'details.*.handlers.quarters.*.ext',
            'details.*.handlers.quarters.*.edge',
            'details.*.handlers.quarters.*.edge.thickness',
            'details.*.handlers.quarters.*.edge.width',

            'details.*.handlers.cutouts',
            'details.*.handlers.cutouts.*.subtype',
            'details.*.handlers.cutouts.*.subside',
            'details.*.handlers.cutouts.*.side',
            'details.*.handlers.cutouts.*.x',
            'details.*.handlers.cutouts.*.y',
            'details.*.handlers.cutouts.*.height',
            'details.*.handlers.cutouts.*.width',
            'details.*.handlers.cutouts.*.depth',
            'details.*.handlers.cutouts.*.fullDepth',
            'details.*.handlers.cutouts.*.r',
            'details.*.handlers.cutouts.*.ext',
            'details.*.handlers.cutouts.*.edge',
            'details.*.handlers.cutouts.*.edge.thickness',
            'details.*.handlers.cutouts.*.edge.width',

            'details.*.handlers.corners',
            'details.*.handlers.corners.*.subtype',
            'details.*.handlers.corners.*.angle',
            'details.*.handlers.corners.*.x',
            'details.*.handlers.corners.*.y',
            'details.*.handlers.corners.*.type',
            'details.*.handlers.corners.*.r',
            'details.*.handlers.corners.*.edge',
            'details.*.handlers.corners.*.edge.thickness',
            'details.*.handlers.corners.*.edge.width'
        ];
    }
}