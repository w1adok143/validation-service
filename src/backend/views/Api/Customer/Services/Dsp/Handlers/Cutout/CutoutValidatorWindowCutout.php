<?php

namespace Kronas\Api\Customer\Services\Dsp\Handlers\Cutout;

use Kronas\Api\Customer\Services\Dsp\DspHandlerException;

class CutoutValidatorWindowCutout extends CutoutValidator
{
    /**
     * Перевірити виріз
     *
     * @return void
     * @throws DspHandlerException
     */
    public function verifyCutout(): void
    {
        $this->verifyDetailSize();
        $this->verifyCutoutWidth();
        $this->verifyCutoutRadius();
        $this->verifyCutoutRetreat();
        $this->verifyCutoutEdge();
    }
}