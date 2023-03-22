<?php

namespace Kronas\Api\Customer\Services\Dsp;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rule;
use Kronas\Api\Customer\Services\Dsp\Handlers\Corner\CornerConfig;
use Kronas\Api\Customer\Services\Dsp\Handlers\Cutout\CutoutConfig;
use Kronas\Api\Customer\Services\Dsp\Handlers\Edge\EdgeConfig;
use Kronas\Api\Customer\Services\Dsp\Handlers\Groove\GrooveConfig;
use Kronas\Api\Customer\Services\Dsp\Handlers\Hole\HoleConfig;
use Kronas\Api\Customer\Services\Dsp\Handlers\Quarter\QuarterConfig;
use Kronas\Lib\Validator\Validator;

class DspProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(EdgeConfig::class, function() {
            $setting = new EdgeConfig();
            $setting->load();

            return $setting->getConfig();
        });

        $this->app->singleton(HoleConfig::class, function() {
            $setting = new HoleConfig();
            $setting->load();

            return $setting->getConfig();
        });

        $this->app->singleton(GrooveConfig::class, function() {
            $setting = new GrooveConfig();
            $setting->load();

            return $setting->getConfig();
        });

        $this->app->singleton(QuarterConfig::class, function() {
            $setting = new QuarterConfig();
            $setting->load();

            return $setting->getConfig();
        });

        $this->app->singleton(CutoutConfig::class, function() {
            $setting = new CutoutConfig();
            $setting->load();

            return $setting->getConfig();
        });

        $this->app->singleton(CornerConfig::class, function() {
            $setting = new CornerConfig();
            $setting->load();

            return $setting->getConfig();
        });

        $this->app->bind(Validator::class, fn() => (new Validator(
            rules: [
                'department' => [
                    'required',
                    'integer'
                ],
                'details' => [
                    'required',
                    'array'
                ],

                'details.*.material' => [
                    'required'
                ],
                'details.*.material.thickness' => [
                    'required',
                    'numeric',
                    'min:0'
                ],

                'details.*.height' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.width' => [
                    'required',
                    'numeric',
                    'min:0'
                ],

                'details.*.handlers' => [
                    'required'
                ],
                'details.*.handlers.edges' => [
                    'nullable'
                ],

                'details.*.handlers.edges.left' => [
                    'nullable'
                ],
                'details.*.handlers.edges.left.thickness' => [
                    'required_unless:details.*.handlers.edges.left,null',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.edges.left.width' => [
                    'required_unless:details.*.handlers.edges.left,null',
                    'numeric',
                    'min:0'
                ],

                'details.*.handlers.edges.top' => [
                    'nullable'
                ],
                'details.*.handlers.edges.top.thickness' => [
                    'required_unless:details.*.handlers.edges.top,null',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.edges.top.width' => [
                    'required_unless:details.*.handlers.edges.top,null',
                    'numeric',
                    'min:0'
                ],

                'details.*.handlers.edges.right' => [
                    'nullable'
                ],
                'details.*.handlers.edges.right.thickness' => [
                    'required_unless:details.*.handlers.edges.right,null',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.edges.right.width' => [
                    'required_unless:details.*.handlers.edges.right,null',
                    'numeric',
                    'min:0'
                ],

                'details.*.handlers.edges.bottom' => [
                    'nullable'
                ],
                'details.*.handlers.edges.bottom.thickness' => [
                    'required_unless:details.*.handlers.edges.bottom,null',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.edges.bottom.width' => [
                    'required_unless:details.*.handlers.edges.bottom,null',
                    'numeric',
                    'min:0'
                ],

                'details.*.handlers.holes' => [
                    'present',
                    'array'
                ],
                'details.*.handlers.holes.*.subtype' => [
                    'nullable',
                    'string'
                ],
                'details.*.handlers.holes.*.side' => [
                    'required',
                    Rule::in(['front', 'back', 'left', 'top', 'right', 'bottom'])
                ],
                'details.*.handlers.holes.*.x' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.holes.*.y' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.holes.*.z' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.holes.*.diam' => [
                    'required',
                    'numeric',
                    'min:2'
                ],
                'details.*.handlers.holes.*.depth' => [
                    'required',
                    'numeric',
                    'min:0'
                ],

                'details.*.handlers.grooves' => [
                    'present',
                    'array'
                ],
                'details.*.handlers.grooves.*.subtype' => [
                    'nullable',
                    'string'
                ],
                'details.*.handlers.grooves.*.side' => [
                    'required',
                    Rule::in(['front', 'back', 'left', 'top', 'right', 'bottom'])
                ],
                'details.*.handlers.grooves.*.direction' => [
                    'required',
                    Rule::in(['horizontal', 'vertical'])
                ],
                'details.*.handlers.grooves.*.x' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.grooves.*.y' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.grooves.*.z' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.grooves.*.height' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.grooves.*.width' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.grooves.*.depth' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.grooves.*.fullDepth' => [
                    'required',
                    'boolean'
                ],
                'details.*.handlers.grooves.*.isFull' => [
                    'required',
                    'boolean'
                ],
                'details.*.handlers.grooves.*.r' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.grooves.*.ext' => [
                    'required',
                    'boolean'
                ],
                'details.*.handlers.grooves.*.edge' => [
                    'present'
                ],
                'details.*.handlers.grooves.*.edge.thickness' => [
                    'required_unless:details.*.handlers.grooves.*.edge,null',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.grooves.*.edge.width' => [
                    'required_unless:details.*.handlers.grooves.*.edge,null',
                    'numeric',
                    'min:0'
                ],

                'details.*.handlers.quarters' => [
                    'present',
                    'array'
                ],
                'details.*.handlers.quarters.*.subtype' => [
                    'nullable',
                    'string'
                ],
                'details.*.handlers.quarters.*.subside' => [
                    'nullable',
                    Rule::in(['left', 'top', 'right', 'bottom'])
                ],
                'details.*.handlers.quarters.*.side' => [
                    'required',
                    Rule::in(['front', 'back', 'left', 'top', 'right', 'bottom'])
                ],
                'details.*.handlers.quarters.*.x' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.quarters.*.y' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.quarters.*.height' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.quarters.*.width' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.quarters.*.depth' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.quarters.*.fullDepth' => [
                    'required',
                    'boolean'
                ],
                'details.*.handlers.quarters.*.isFull' => [
                    'required',
                    'boolean'
                ],
                'details.*.handlers.quarters.*.r' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.quarters.*.ext' => [
                    'required',
                    'boolean'
                ],
                'details.*.handlers.quarters.*.edge' => [
                    'present'
                ],
                'details.*.handlers.quarters.*.edge.thickness' => [
                    'required_unless:details.*.handlers.quarters.*.edge,null',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.quarters.*.edge.width' => [
                    'required_unless:details.*.handlers.quarters.*.edge,null',
                    'numeric',
                    'min:0'
                ],

                'details.*.handlers.cutouts' => [
                    'present',
                    'array'
                ],
                'details.*.handlers.cutouts.*.subtype' => [
                    'present',
                    'nullable',
                    'string'
                ],
                'details.*.handlers.cutouts.*.subside' => [
                    'nullable',
                    Rule::in(['left', 'top', 'right', 'bottom'])
                ],
                'details.*.handlers.cutouts.*.side' => [
                    'required',
                    Rule::in(['front', 'back', 'left', 'top', 'right', 'bottom'])
                ],
                'details.*.handlers.cutouts.*.x' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.cutouts.*.y' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.cutouts.*.height' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.cutouts.*.width' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.cutouts.*.depth' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.cutouts.*.fullDepth' => [
                    'required',
                    'boolean'
                ],
                'details.*.handlers.cutouts.*.r' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.cutouts.*.ext' => [
                    'required',
                    'boolean'
                ],
                'details.*.handlers.cutouts.*.edge' => [
                    'present'
                ],
                'details.*.handlers.cutouts.*.edge.thickness' => [
                    'required_unless:details.*.handlers.cutouts.*.edge,null',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.cutouts.*.edge.width' => [
                    'required_unless:details.*.handlers.cutouts.*.edge,null',
                    'numeric',
                    'min:0'
                ],

                'details.*.handlers.corners' => [
                    'present',
                    'array'
                ],
                'details.*.handlers.corners.*.subtype' => [
                    'nullable',
                    'string'
                ],
                'details.*.handlers.corners.*.angle' => [
                    'required',
                    Rule::in(['left_bottom', 'left_top', 'right_bottom', 'right_top'])
                ],
                'details.*.handlers.corners.*.x' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.corners.*.y' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.corners.*.type' => [
                    'required',
                    'string',
                    Rule::in(['line', 'radius'])
                ],
                'details.*.handlers.corners.*.r' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.corners.*.edge' => [
                    'present'
                ],
                'details.*.handlers.corners.*.edge.thickness' => [
                    'required_unless:details.*.handlers.corners.*.edge,null',
                    'numeric',
                    'min:0'
                ],
                'details.*.handlers.corners.*.edge.width' => [
                    'required_unless:details.*.handlers.corners.*.edge,null',
                    'numeric',
                    'min:0'
                ]
            ],
            errors: Config::get('lang' . '/' . lang() . '/' . 'message_validation') ?? []
        ))->init());
    }
}