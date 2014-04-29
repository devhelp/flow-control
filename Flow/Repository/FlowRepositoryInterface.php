<?php

namespace Devhelp\Component\FlowControl\Flow\Repository;


use Devhelp\Component\FlowControl\Flow\Flow;

interface FlowRepositoryInterface
{
    /**
     * @param $flowId
     * @return Flow
     */
    public function getFlow($flowId);
} 