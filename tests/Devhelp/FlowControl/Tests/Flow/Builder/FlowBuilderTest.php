<?php

namespace Devhelp\FlowControl\Tests\Builder;


use Devhelp\FlowControl\Flow\Builder\FlowBuilder;
use Devhelp\FlowControl\Flow\Flow;
use Devhelp\FlowControl\Tests\BaseFlowControlTestCase;

class FlowBuilderTest extends BaseFlowControlTestCase
{

    /**
     * @test
     */
    public function returnsNullIfDefinitionDoesNotExist()
    {
        $builder = new FlowBuilder();

        $this->assertNull($builder->build('test-definition'));
    }

    /**
     * @test
     */
    public function createsFlowFromDefinition()
    {
        $flowId = 'test-flow';
        $moves = array(
            'step_1' => array('step_2'),
            'step_2' => array('step_3'),
            'step_3' => array('step_4'),
        );
        $entryPoints = array('step_1', 'step_2');

        $expected = new Flow($flowId, $moves, $entryPoints);

        $builder = new FlowBuilder();

        $definitions = array(
            $flowId => array(
                'moves' => $moves,
                'entry_points' => $entryPoints
            )
        );

        $builder->setDefinitions($definitions);

        $this->assertEquals($expected, $builder->build($flowId));
    }
}
