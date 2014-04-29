<?php

namespace Devhelp\FlowControl;

interface FlowControlInterface
{
    public function setFlowSteps(array $flowSteps);

    /**
     * @return boolean
     */
    public function canAccess($step, $flowId);

    /**
     * @return array
     */
    public function resolveValid(array $nextSteps);
} 