<?php

namespace Devhelp\Component\FlowControl\Flow\Repository;


use Devhelp\Component\FlowControl\Flow\Builder\FlowBuilder;

class LazyFlowRepository implements FlowRepositoryInterface
{

    /**
     * @var FlowBuilder
     */
    protected $builder;

    /**
     * @var array
     */
    protected $flows;

    public function __construct(FlowBuilder $builder)
    {
        $this->builder = $builder;
        $this->flows = array();
    }

    public function getFlow($flowId)
    {
        if (!isset($this->flows[$flowId])) {
            $this->flows[$flowId] = $this->builder->build($flowId);
        }

        return $this->flows[$flowId];
    }
}