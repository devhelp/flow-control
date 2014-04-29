<?php

namespace Devhelp\FlowControl\Tests\Flow;


use Devhelp\FlowControl\Flow\Flow;
use Devhelp\FlowControl\Tests\BaseFlowControlTestCase;

class FlowTest extends BaseFlowControlTestCase
{

    /**
     * @test
     */
    public function onSetMovesAllStepsAreRetrieved()
    {
        $flow = $this->getFlow();

        $expectedSteps = array('step_2', 'step_1', 'step_3', 'step_4');

        $this->assertEquals($expectedSteps, $flow->getSteps());
    }

    /**
     * @test
     * @expectedException Devhelp\FlowControl\Exception\StepDoesNotExistException
     * @expectedExceptionMessage step [step_X, step_Y] does not exist in flow [test-flow]
     */
    public function setEntryPointsThrowsExceptionIfStepsDoNotExist()
    {
        $flow = $this->getFlow();

        $entryPoints = array('step_X', 'step_Y');

        $flow->setEntryPoints($entryPoints);
    }

    /**
     * @test
     */
    public function hasStepReturnsTrueIfStepExists()
    {
        $flow = $this->getFlow();

        $this->assertTrue($flow->hasStep('step_1'));
        $this->assertTrue($flow->hasStep('step_2'));
        $this->assertTrue($flow->hasStep('step_3'));
        $this->assertTrue($flow->hasStep('step_4'));
    }

    /**
     * @test
     */
    public function hasStepReturnsFalseIfStepDoesNotExist()
    {
        $flow = $this->getFlow();

        $this->assertFalse($flow->hasStep('step_5'));
    }

    /**
     * @test
     * @dataProvider providerMoves
     */
    public function isMoveValidReturnsExpectedValues($from, $to, $isValid)
    {
        $flow = $this->getFlow();

        $this->assertEquals($isValid, $flow->isMoveValid($from, $to));
    }

    public function providerMoves()
    {
        return array(
            array('step_1', 'step_2', true),
            array('step_2', 'step_3', true),
            array('step_2', 'step_4', true),
            array('step_4', 'step_2', true),
            array('step_1', 'step_3', false),
            array('step_2', 'step_1', false),
            array('step_4', 'step_1', false),
            array('step_4', 'step_3', false),
        );
    }

    /**
     * @return Flow
     */
    protected function getFlow()
    {
        $map = array(
            'step_1' => array('step_2'),
            'step_2' => array('step_3', 'step_4'),
            'step_4' => array('step_2')
        );

        $flow = new Flow('test-flow', $map);

        return $flow;
    }
}
