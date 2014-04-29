<?php

namespace Devhelp\FlowControl\Tests\Repository;

use Devhelp\FlowControl\Flow\Repository\SimpleFlowRepository;
use Devhelp\FlowControl\Tests\BaseFlowControlTestCase;

class SimpleFlowRepositoryTest extends BaseFlowControlTestCase
{
    /**
     * @test
     */
    public function getFlowReturnsNullIfFlowDoesNotExists()
    {
        $flowRepository = new SimpleFlowRepository();

        $this->assertNull($flowRepository->getFlow('some-flow'));
    }

    /**
     * @test
     */
    public function getFlowReturnsFlowIfItExists()
    {
        $flowA = $this->getFlowMock();

        $flowA
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));

        $flowB = $this->getFlowMock();

        $flowB
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(2));

        $flowC = $this->getFlowMock();

        $flowC
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(3));

        $flows = array($flowA, $flowB, $flowC);

        $flowRepository = new SimpleFlowRepository($flows);

        $this->assertEquals($flowB, $flowRepository->getFlow($flowB->getId()));
    }
}