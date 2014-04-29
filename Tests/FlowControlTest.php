<?php

namespace Devhelp\Component\FlowControl\Tests;

use Devhelp\Component\FlowControl\FlowControl;

class FlowControlTest extends BaseFlowControlTestCase
{

    /**
     * @test
     * @expectedException Devhelp\Component\FlowControl\Exception\FlowDoesNotExistException
     */
    public function canAccessThrowsExceptionIfFlowDoesNotExists()
    {
        $flowControl = new FlowControl($this->getFlowRepositoryMock());

        $flowControl->canAccess('test-step', 'test-flow');
    }

    /**
     * @test
     */
    public function canAccessReturnsFalseIfStepDoesNotExistsInTheFlow()
    {
        $flowMock = $this->getFlowMock();

        $flowMock
            ->expects($this->once())
            ->method('hasStep')
            ->will($this->returnValue(false));

        $testFlowId = 'test-flow';

        $flows = array(
            $testFlowId => $flowMock,
        );

        $flowControl = new FlowControl($this->getFlowRepositoryMock($flows));

        $this->assertFalse($flowControl->canAccess('step', $testFlowId));
    }

    /**
     * @test
     */
    public function canAccessReturnsTrueIfCurrentStepIsNotSetAndStepIsAnEntryPoint()
    {
        $flowMock = $this->getFlowMock();

        $flowMock
            ->expects($this->once())
            ->method('hasStep')
            ->will($this->returnValue(true));

        $flowMock
            ->expects($this->once())
            ->method('getEntryPoints')
            ->will($this->returnValue(array('step')));

        $testFlowId = 'test-flow';

        $flows = array(
            $testFlowId => $flowMock,
        );

        $flowControl = new FlowControl($this->getFlowRepositoryMock($flows));

        $this->assertTrue($flowControl->canAccess('step', $testFlowId));
    }

    /**
     * @test
     */
    public function canAccessReturnsFalseIfCurrentStepIsNotSetAndStepIsNotAnEntryPoint()
    {
        $flowMock = $this->getFlowMock();

        $flowMock
            ->expects($this->once())
            ->method('hasStep')
            ->will($this->returnValue(true));

        $flowMock
            ->expects($this->once())
            ->method('getEntryPoints')
            ->will($this->returnValue(array()));

        $testFlowId = 'test-flow';

        $flows = array(
            $testFlowId => $flowMock,
        );

        $flowControl = new FlowControl($this->getFlowRepositoryMock($flows));

        $this->assertFalse($flowControl->canAccess('step', $testFlowId));
    }

    /**
     * @test
     * @dataProvider providerIsMoveValid
     */
    public function canAccessReturnsWhatIsMoveValidReturnsWhenCalled($isMoveValid)
    {
        $flowMock = $this->getFlowMock();

        $flowMock
            ->expects($this->once())
            ->method('hasStep')
            ->will($this->returnValue(true));

        $flowMock
            ->expects($this->once())
            ->method('isMoveValid')
            ->will($this->returnValue($isMoveValid));

        $testFlowId = 'test-flow';

        $flows = array(
            $testFlowId => $flowMock,
        );

        $stepMock = $this->getStepMock();

        $flowControl = new FlowControl($this->getFlowRepositoryMock($flows));

        $flowControl->setFlowSteps(array($testFlowId => $stepMock));

        $this->assertEquals($isMoveValid, $flowControl->canAccess('step', $testFlowId));
    }

    public function providerIsMoveValid()
    {
        return array(
            array(true),
            array(false),
        );
    }

    /**
     * @test
     */
    public function resolveValidReturnsEmptyFlowStepsWhenNoStepsWereGiven()
    {
        $flowControl = new FlowControl($this->getFlowRepositoryMock(array()));

        $this->assertEmpty($flowControl->resolveValid(array()));
    }

    /**
     * @test
     */
    public function resolveValidReturnsStepsOnlyForValidMoves()
    {
        $flowSteps = array(
            'flow_a' => 'step_1',
            'flow_b' => 'step_2',
            'flow_c' => 'step_3',
        );

        $nextSteps = array(
            'flow_a' => 'step_2',
            'flow_b' => 'step_3',
            'flow_c' => 'step_4',
        );

        $validSteps = array(
            'flow_a' => 'step_2',
            'flow_c' => 'step_4',
        );

        $flowMockA = $this->getFlowMock();

        $flowMockA
            ->expects($this->any())
            ->method('hasStep')
            ->will($this->returnValue(true));

        $flowMockA
            ->expects($this->any())
            ->method('isMoveValid')
            ->will($this->returnValue(true));

        $flowMockB = $this->getFlowMock();

        $flowMockB
            ->expects($this->any())
            ->method('hasStep')
            ->will($this->returnValue(false));

        $flowMockC = clone $flowMockA;

        $flows = array(
            'flow_a' => $flowMockA,
            'flow_b' => $flowMockB,
            'flow_c' => $flowMockC,
        );

        $flowControl = new FlowControl($this->getFlowRepositoryMock($flows));

        $flowControl->setFlowSteps($flowSteps);

        $this->assertEquals($validSteps, $flowControl->resolveValid($nextSteps));
    }
}