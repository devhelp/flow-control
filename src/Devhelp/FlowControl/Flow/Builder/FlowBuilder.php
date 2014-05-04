<?php

namespace Devhelp\FlowControl\Flow\Builder;


use Devhelp\FlowControl\Flow\Flow;

/**
 * Build flows from flow definition
 */
class FlowBuilder
{
    protected $definitions;

    public function setDefinitions(array $definitions = array())
    {
        $this->definitions = $definitions;

        return $this;
    }

    /**
     * Builds and returns flow of given $flowId
     *
     * @param $flowId
     * @return Flow|null
     */
    public function build($flowId)
    {
        if (!isset($this->definitions[$flowId])) {
            return;
        }

        $flowDefinition = $this->definitions[$flowId];

        return new Flow($flowId, $flowDefinition['moves'], $flowDefinition['entry_points']);
    }
}
