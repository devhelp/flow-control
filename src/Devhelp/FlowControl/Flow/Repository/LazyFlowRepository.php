<?php

namespace Devhelp\FlowControl\Flow\Repository;


use Devhelp\FlowControl\Flow\Builder\FlowBuilder;

/**
 * Repository uses FlowBuilder in order to build the full Flow object
 * only on demand. Returns the same flow instance if it was already built
 */
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

    /**
     * {@inheritdoc}
     */
    public function getFlow($flowId)
    {
        if (!isset($this->flows[$flowId])) {
            $this->flows[$flowId] = $this->builder->build($flowId);
        }

        return $this->flows[$flowId];
    }
}
