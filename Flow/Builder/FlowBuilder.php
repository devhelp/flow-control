<?php

namespace Devhelp\Component\FlowControl\Flow\Builder;


use Devhelp\Component\FlowControl\Flow\Flow;

class FlowBuilder
{
    protected $definitions;

    public function setDefinitions(array $definitions = array())
    {
        $this->definitions = $definitions;

        return $this;
    }

    public function build($flowId)
    {
        if (!isset($this->definitions[$flowId])) {
            return;
        }

        $flowDefinition = $this->definitions[$flowId];

        return new Flow($flowId, $flowDefinition['moves'], $flowDefinition['entry_points']);
    }
}
