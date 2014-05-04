<?php

namespace Devhelp\FlowControl\Exception;


class StepDoesNotExistException extends \Exception
{

    public function __construct($step, $flowId, $code = 0, \Exception $previous = null)
    {
        $message = sprintf(
            "step [%s] does not exist in flow [%s]",
            $step,
            $flowId
        );

        parent::__construct($message, $code, $previous);
    }
}
