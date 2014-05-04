<?php

namespace Devhelp\FlowControl\Flow;

use Devhelp\FlowControl\Exception\StepDoesNotExistException;

class Flow
{
    protected $id;
    protected $moves;
    protected $entryPoints;
    protected $steps;

    public function __construct($id, array $moves = array(), array $entryPoints = array())
    {
        $this->id = $id;
        $this->setMoves($moves);
        $this->setEntryPoints($entryPoints);
    }

    /**
     * sets steps that are entry points in the flow. Entry points must be existing
     * steps so moves must be set first
     *
     * @param array $entryPoints
     * @return Flow
     * @throws StepDoesNotExistException
     */
    public function setEntryPoints(array $entryPoints = array())
    {
        $nonExistingSteps = array_diff($entryPoints, $this->steps);

        if ($nonExistingSteps) {
            $steps = implode(', ', $nonExistingSteps);
            throw new StepDoesNotExistException($steps, $this->id);
        }

        $this->entryPoints = $entryPoints;

        return $this;
    }

    /**
     * @return array
     */
    public function getEntryPoints()
    {
        return $this->entryPoints;
    }

    /**
     * sets flow moves. Steps from the array will be treated as flow steps.
     *
     * @param array $moves
     * @return $this
     */
    public function setMoves(array $moves = array())
    {
        $this->moves = $moves;

        $steps = array();

        foreach ($moves as $step => $nextSteps) {
            $steps = array_merge($steps, $nextSteps, array($step));
        }

        $this->steps = array_unique($steps);

        return $this;
    }

    /**
     * checks if move from step $from to step $to is valid
     *
     * @param $from
     * @param $to
     * @return bool
     */
    public function isMoveValid($from, $to)
    {
        return isset($this->moves[$from]) && in_array($to, $this->moves[$from]);
    }

    /**
     * @param $step
     * @return bool
     */
    public function hasStep($step)
    {
        return in_array($step, $this->steps);
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getMoves()
    {
        return $this->moves;
    }

    /**
     * @return array
     */
    public function getSteps()
    {
        return $this->steps;
    }
}
