<?php

namespace Devhelp\FlowControl;

use Devhelp\FlowControl\Exception\FlowDoesNotExistException;
use Devhelp\FlowControl\Flow\Repository\FlowRepositoryInterface;

class FlowControl implements FlowControlInterface
{

    /**
     * @var array
     */
    protected $flowSteps;

    /**
     * @var FlowRepositoryInterface
     */
    protected $flowRepository;

    public function __construct(FlowRepositoryInterface $flowRepository)
    {
        $this->flowSteps = array();
        $this->flowRepository = $flowRepository;
    }

    /**
     * @param array $flowSteps
     * @return FlowControl
     */
    public function setFlowSteps(array $flowSteps)
    {
        $this->flowSteps = $flowSteps;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function canAccess($step, $flowId)
    {
        $flow = $this->flowRepository->getFlow($flowId);

        if (is_null($flow)) {
            throw new FlowDoesNotExistException($flowId);
        }

        if (!$flow->hasStep($step)) {
            return false;
        }

        $currentStep = isset($this->flowSteps[$flowId]) ? $this->flowSteps[$flowId] : null;

        if (is_null($currentStep)) {
            return in_array($step, $flow->getEntryPoints());
        } elseif ($currentStep === $step) {
            return true;
        }

        return $flow->isMoveValid($currentStep, $step);
    }

    /**
     * {@inheritdoc}
     */
    public function resolveValid(array $nextSteps)
    {
        $validSteps = array();

        foreach ($nextSteps as $flowId => $nextStep) {
            if ($this->canAccess($nextStep, $flowId)) {

                /*
                 * only one valid step per flow - otherwise it we may have conflict because
                 * if we have many valid steps then to which one should we go next ?
                 */
                $validSteps[$flowId] =  $nextStep;
            }
        }

        return $validSteps;
    }
}
