<?php

namespace Docs\Api\Customer\Services\Dsp;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface IDsp
{
    /**
     * @OA\Post(
     *   tags={"Обробка ДСП/ДВП/МДФ"},
     *   summary="Валідація операцій",
     *   path="/api/services/dsp",
     *   @OA\Response(response="200", description="success"),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       @OA\Property(property="lang", type="string", example="ua"),
     *       @OA\Property(property="department", type="integer", example="54096"),
     *       @OA\Property (property="details", type="array", @OA\Items({
     *         @OA\Property(property="material", type="object",
     *           @OA\Property(property="thickness", type="integer", example="18")
     *         ),
     *
     *         @OA\Property(property="height", type="integer", example="600"),
     *         @OA\Property(property="width", type="integer", example="600"),
     *
     *         @OA\Property(property="handlers", type="object",
     *           @OA\Property(property="edges", type="object",
     *             @OA\Property(property="left", type="object",
     *               @OA\Property(property="thickness", type="integer", example="1"),
     *               @OA\Property(property="width", type="integer", example="22")
     *            )),
     *
     *           @OA\Property(property="holes", type="array", @OA\Items({
     *             @OA\Property(property="subtype", type="object", example="null"),
     *             @OA\Property(property="side", type="string", example="front"),
     *             @OA\Property(property="x", type="integer", example="100"),
     *             @OA\Property(property="y", type="integer", example="100"),
     *             @OA\Property(property="z", type="integer", example="0"),
     *             @OA\Property(property="depth", type="integer", example="5"),
     *             @OA\Property(property="diam", type="integer", example="5")
     *           })),
     *
     *           @OA\Property(property="grooves", type="array", @OA\Items({
     *             @OA\Property(property="subtype", type="object", example="null"),
     *             @OA\Property(property="side", type="string", example="front"),
     *             @OA\Property(property="direction", type="string", example="horizontal"),
     *             @OA\Property(property="x", type="integer", example="100"),
     *             @OA\Property(property="y", type="integer", example="100"),
     *             @OA\Property(property="z", type="integer", example="0"),
     *             @OA\Property(property="width", type="integer", example="100"),
     *             @OA\Property(property="height", type="integer", example="100"),
     *             @OA\Property(property="depth", type="integer", example="5"),
     *             @OA\Property(property="fullDepth", type="boolean", example="false"),
     *             @OA\Property(property="isFull", type="boolean", example="false"),
     *             @OA\Property(property="r", type="integer", example="3"),
     *             @OA\Property(property="edge", type="object", example="null"),
     *             @OA\Property(property="ext", type="boolean", example="false")
     *           })),
     *
     *           @OA\Property(property="quarters", type="array", @OA\Items({
     *             @OA\Property(property="subtype", type="object", example="null"),
     *             @OA\Property(property="subside", type="string", example="bottom"),
     *             @OA\Property(property="side", type="string", example="front"),
     *             @OA\Property(property="x", type="integer", example="0"),
     *             @OA\Property(property="y", type="integer", example="0"),
     *             @OA\Property(property="width", type="integer", example="5"),
     *             @OA\Property(property="height", type="integer", example="600"),
     *             @OA\Property(property="depth", type="integer", example="5"),
     *             @OA\Property(property="fullDepth", type="boolean", example="false"),
     *             @OA\Property(property="isFull", type="boolean", example="false"),
     *             @OA\Property(property="r", type="integer", example="0"),
     *             @OA\Property(property="edge", type="object", example="null"),
     *             @OA\Property(property="ext", type="boolean", example="false")
     *           })),
     *
     *           @OA\Property(property="cutouts", type="array", @OA\Items({
     *             @OA\Property(property="subtype", type="string", example="rect"),
     *             @OA\Property(property="subside", type="string", example="bottom"),
     *             @OA\Property(property="side", type="string", example="front"),
     *             @OA\Property(property="x", type="integer", example="160"),
     *             @OA\Property(property="y", type="integer", example="100"),
     *             @OA\Property(property="width", type="integer", example="100"),
     *             @OA\Property(property="height", type="integer", example="100"),
     *             @OA\Property(property="depth", type="integer", example="0"),
     *             @OA\Property(property="fullDepth", type="boolean", example="true"),
     *             @OA\Property(property="r", type="integer", example="10"),
     *             @OA\Property(property="edge", type="object", example="null"),
     *             @OA\Property(property="ext", type="boolean", example="false")
     *           })),
     *
     *           @OA\Property(property="corners", type="array", @OA\Items({
     *             @OA\Property(property="subtype", type="object", example="null"),
     *             @OA\Property(property="angle", type="string", example="left_bottom"),
     *             @OA\Property(property="x", type="integer", example="0"),
     *             @OA\Property(property="y", type="integer", example="0"),
     *             @OA\Property(property="type", type="integer", example="radius"),
     *             @OA\Property(property="r", type="integer", example="10"),
     *             @OA\Property(property="edge", type="object", example="null")
     *           }))
     * )})))))
     */
    public function verify(Request $request): JsonResponse;
}