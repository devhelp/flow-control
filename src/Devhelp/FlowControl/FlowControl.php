<?php

namespace Devhelp\FlowControl;

use Devhelp\FlowControl\Exception\FlowDoesNotExistException;
use Devhelp\FlowControl\Flow\Repository\FlowRepositoryInterface;

class FlowControl
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
     * checks if it is possible to move to step $step in the flow
     * of given $flowId
     *
     * @param $step
     * @param $flowId
     * @return bool
     * @throws Exception\FlowDoesNotExistException
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
     * returns array with all valid moves from the $moves array
     *
     * @param array $moves
     * @return array
     */
    public function resolveValid(array $moves)
    {
        $validMoves = array();

        foreach ($moves as $flowId => $step) {
            if ($this->canAccess($step, $flowId)) {

                /*
                 * only one valid step per flow - otherwise it we may have conflict because
                 * if we have many valid steps then to which one should we go next ?
                 */
                $validMoves[$flowId] =  $step;
            }
        }

        return $validMoves;
    }
}
