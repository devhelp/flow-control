<?php

namespace Devhelp\Component\FlowControl\Tests;


abstract class BaseFlowControlTestCase extends \PHPUnit_Framework_TestCase
{

    protected function getStepMock($index = 1)
    {
        return $index;
    }

    protected function getFlowMock()
    {
        $mock = $this
            ->getMockBuilder('Devhelp\Component\FlowControl\Flow\Flow')
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }

    protected function getFlowRepositoryMock($flows = array())
    {
        $mock = $this
            ->getMockBuilder('Devhelp\Component\FlowControl\Flow\Repository\FlowRepositoryInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('getFlow')
            ->will(
                $this->returnCallback(
                    function ($flowId) use ($flows) {
                        return @$flows[$flowId];
                    }
                )
            );

        return $mock;
    }

    protected function getFlowBuilderMock($flows)
    {
        $mock = $this
            ->getMockBuilder('Devhelp\Component\FlowControl\Flow\Builder\FlowBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('build')
            ->will(
                $this->returnCallback(
                    function ($flowId) use ($flows) {
                        return isset($flows[$flowId]) ? $flows[$flowId] : null;
                    }
                )
            );

        return $mock;
    }
} 