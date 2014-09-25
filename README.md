[![Build Status](https://travis-ci.org/devhelp/flow-control.png)](https://travis-ci.org/devhelp/flow-control) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/f201687c-8951-4ea9-9fce-aed0a4d2046a/mini.png)](https://insight.sensiolabs.com/projects/f201687c-8951-4ea9-9fce-aed0a4d2046a)

## Installation

Composer is preferred to install Flow Control, please check [composer website](http://getcomposer.org) for more information.

```
$ composer require 'devhelp/flow-control:dev-master'
```

## Purpose

Flow Control is a simple tool that allows to control the flow defined in the Flow class.

Two main classes are Flow and FlowControl. Flow is defined using moves array and entry points.
FlowControl is used to control state changes in flows.

Concept of the flow is general, some obvious use case can be, to define and control flow in checkout process

## Usage

```
//two way flow with optional 'step_c'
$exampleFlowA = new Flow(
    'flow_a',
    array(
        'step_a' => array('step_b'),
        'step_b' => array('step_a', 'step_c', 'step_d'),
        'step_c' => array('step_b', 'step_d'),
        'step_d' => array('step_b', 'step_c')
    ),
    array('step_a')
);

//one way flow
$exampleFlowB = new Flow(
    'flow_b',
    array(
        'step_a' => array('step_b'),
        'step_b' => array('step_c'),
        'step_c' => array('step_d'),
    ),
    array('step_a')
);

$flows = array($exampleFlowA, $exampleFlowB);

$repository = new SimpleFlowRepository($flows);

$flowControl = new FlowControl($repository);

/**
 * origin of current steps is out of the scope of FlowControl component,
 * the same is regarding changing the state in the flow. FlowControl
 * is only for resolving valid moves
 */
$currentSteps = array(
    'flow_a' => 'step_c',
);

$flowControl->setFlowSteps($currentSteps);

$flowControl->isAllowed('step_d', 'flow_a'); //true
$flowControl->isAllowed('step_a', 'flow_a'); //false
$flowControl->isAllowed('step_c', 'flow_a'); //true
$flowControl->isAllowed('step_a', 'flow_b'); //true
$flowControl->isAllowed('step_b', 'flow_b'); //false

$moves = array(
    'flow_a' => 'step_d',
    'flow_b' => 'step_b',
);

$validMoves = $flowControl->resolveValid($moves); //array('flow_a' => 'step_d')
```

## Credits

Brought to you by : Devhelp.pl (http://devhelp.pl)
