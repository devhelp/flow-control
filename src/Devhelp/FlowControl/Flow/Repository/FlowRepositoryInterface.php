<?php

namespace Devhelp\FlowControl\Flow\Repository;


use Devhelp\FlowControl\Flow\Flow;

interface FlowRepositoryInterface
{
    /**
     * returns flow with given $flowId
     *
     * @param $flowId
     * @return Flow
     */
    public function getFlow($flowId);
}
