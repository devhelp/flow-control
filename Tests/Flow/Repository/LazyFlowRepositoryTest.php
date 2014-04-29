<?php

namespace Devhelp\Component\FlowControl\Tests\Repository;

use Devhelp\Component\FlowControl\Flow\Repository\LazyFlowRepository;
use Devhelp\Component\FlowControl\Tests\BaseFlowControlTestCase;

class LazyFlowRepositoryTest extends BaseFlowControlTestCase
{
    /**
     * @test
     */
    public function getFlowReturnsNullIfFlowDoesNotExists()
    {
        $builder = $this->getFlowBuilderMock(array());

        $flowRepository = new LazyFlowRepository($builder);

        $this->assertNull($flowRepository->getFlow('some-flow'));
    }

    /**
     * @test
     */
    public function getFlowReturnsCachedFlowIfItIsAskedMoreThanOneTimeForTheSameFlowId()
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

        $builder = $this->getFlowBuilderMock($flows);

        $builder
            ->expects($this->exactly(1))
            ->method('build')
            ->will(
                $this->returnCallback(
                    function ($flowId) use ($flows) {
                        return isset($flows[$flowId]) ? $flows[$flowId] : null;
                    }
                )
            );

        $flowRepository = new LazyFlowRepository($builder);

        $this->assertEquals($flowB, $flowRepository->getFlow($flowB->getId()));
        $this->assertEquals($flowB, $flowRepository->getFlow($flowB->getId()));
    }
}