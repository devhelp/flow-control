<?php

namespace Devhelp\Component\FlowControl\Exception;


class FlowDoesNotExistException extends \Exception
{

    public function __construct($flowId, $code = 0, \Exception $previous = null)
    {
        $message = sprintf("flow '%s' does not exist", $flowId);

        parent::__construct($message, $code, $previous);
    }
}
