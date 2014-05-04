<?php

namespace Devhelp\FlowControl\Flow\Repository;


use Devhelp\FlowControl\Flow\Flow;

/**
 * Stores full Flow objects in array
 */
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
     * {@inheritdoc}
     */
    public function getFlow($flowId)
    {
        return isset($this->flows[$flowId]) ? $this->flows[$flowId] : null;
    }
}
