<?php

namespace Devhelp\FlowControl\Flow\Repository;


use Devhelp\FlowControl\Flow\Flow;

interface FlowRepositoryInterface
{
    /**
     * @param $flowId
     * @return Flow
     */
    public function getFlow($flowId);
} 