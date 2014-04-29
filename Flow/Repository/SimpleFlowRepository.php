<?php

namespace Devhelp\Component\FlowControl\Flow\Repository;


use Devhelp\Component\FlowControl\Flow\Flow;

class SimpleFlowRepository implements FlowRepositoryInterface
{
    protected $flows = array();

    public function __construct(array $flows = array())
    {
        foreach ($flows as $flow) {
            $this->addFlow($flow);
        }
    }

    protected function addFlow(Flow $flow)
    {
        $this->flows[$flow->getId()] = $flow;
    }

    /**
     * @param $flowId
     * @return Flow
     */
    public function getFlow($flowId)
    {
        return isset($this->flows[$flowId]) ? $this->flows[$flowId] : null;
    }
}