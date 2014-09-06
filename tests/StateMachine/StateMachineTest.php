<?php

/*
* This file is part of the state_machine package
*
* (c) Michal Wachowski <wachowski.michal@gmail.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace StateMachine;


class StateMachineTest extends \PHPUnit_Framework_TestCase {

    public function testAddState()
    {
        $state = $this->getMock('\StateMachine\StateInterface');
        $state->expects($this->any())->method('getName')->will($this->returnValue('foo'));

        $machine = new StateMachine();
        $machine->addState($state);
        $this->assertEquals(['foo' => $state], $machine->getStates());
    }

    public function testHasState()
    {
        $state = $this->getMock('\StateMachine\StateInterface');
        $state->expects($this->any())->method('getName')->will($this->returnValue('foo'));

        $machine = new StateMachine();

        $this->assertFalse($machine->hasState('foo'));

        $machine->addState($state);

        $this->assertTrue($machine->hasState('foo'));
    }

    public function testGetState()
    {
        $state = $this->getMock('\StateMachine\StateInterface');
        $state->expects($this->any())->method('getName')->will($this->returnValue('foo'));

        $machine = new StateMachine();
        $machine->addState($state);

        $this->assertEquals($state, $machine->getState('foo'));
    }

    public function testSetStates()
    {
        $state = $this->getMock('\StateMachine\StateInterface');
        $state->expects($this->any())->method('getName')->will($this->returnValue('foo'));

        $machine = new StateMachine();
        $machine->setStates([$state]);

        $this->assertEquals(['foo' => $state], $machine->getStates());
    }

    public function testChangeState()
    {
        $payload = $this->getMock('\StateMachine\StatefulInterface');
        $payload->expects($this->any())->method('getState')->will($this->returnValue('initial'));
        $payload->expects($this->once())->method('setState')->with('bar');

        $initial = $this->getMock('\StateMachine\StateInterface');
        $initial->expects($this->any())->method('getName')->will($this->returnValue('initial'));
        $initial->expects($this->any())->method('getTargetStates')->will($this->returnValue(['foo', 'bar']));

        $skipped = $this->getMock('\StateMachine\StateInterface');
        $skipped->expects($this->any())->method('getName')->will($this->returnValue('foo'));
        $skipped->expects($this->any())->method('check')->will($this->returnValue(false));

        $target = $this->getMock('\StateMachine\StateInterface');
        $target->expects($this->any())->method('getName')->will($this->returnValue('bar'));
        $target->expects($this->any())->method('check')->will($this->returnValue(true));
        $target->expects($this->any())->method('execute')->will($this->returnValue($payload));

        $machine = new StateMachine();
        $machine->setStates(
            [
                $initial,
                $skipped,
                $target
            ]
        );

        $this->assertEquals($payload, $machine->changeState($payload));
    }

    public function testChangeWithNoStates()
    {
        $payload = $this->getMock('\StateMachine\StatefulInterface');
        $payload->expects($this->any())->method('getState')->will($this->returnValue('initial'));
        $payload->expects($this->exactly(0))->method('setState');

        $initial = $this->getMock('\StateMachine\StateInterface');
        $initial->expects($this->any())->method('getName')->will($this->returnValue('initial'));
        $initial->expects($this->any())->method('getTargetStates')->will($this->returnValue([]));

        $machine = new StateMachine();
        $machine->addState($initial);
        $this->assertEquals($payload, $machine->changeState($payload));
    }

    public function testChangeWithNoMatchingStates()
    {
        $payload = $this->getMock('\StateMachine\StatefulInterface');
        $payload->expects($this->any())->method('getState')->will($this->returnValue('initial'));
        $payload->expects($this->exactly(0))->method('setState');

        $initial = $this->getMock('\StateMachine\StateInterface');
        $initial->expects($this->any())->method('getName')->will($this->returnValue('initial'));
        $initial->expects($this->any())->method('getTargetStates')->will($this->returnValue(['foo', 'bar']));

        $skipped = $this->getMock('\StateMachine\StateInterface');
        $skipped->expects($this->any())->method('getName')->will($this->returnValue('foo'));
        $skipped->expects($this->any())->method('check')->will($this->returnValue(false));

        $target = $this->getMock('\StateMachine\StateInterface');
        $target->expects($this->any())->method('getName')->will($this->returnValue('bar'));
        $target->expects($this->any())->method('check')->will($this->returnValue(false));

        $machine = new StateMachine();
        $machine->setStates(
            [
                $initial,
                $skipped,
                $target
            ]
        );

        $this->assertEquals($payload, $machine->changeState($payload));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testChangeToStateWithInvalidStartingState()
    {
        $payload = $this->getMock('\StateMachine\StatefulInterface');
        $payload->expects($this->any())->method('getState')->will($this->returnValue('initial'));

        $machine = new StateMachine();
        $machine->changeToState($payload, 'foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testChangeToStateWithInvalidTargetState()
    {
        $payload = $this->getMock('\StateMachine\StatefulInterface');
        $payload->expects($this->any())->method('getState')->will($this->returnValue('initial'));

        $initial = $this->getMock('\StateMachine\StateInterface');
        $initial->expects($this->any())->method('getName')->will($this->returnValue('initial'));
        $initial->expects($this->any())->method('getTargetStates')->will($this->returnValue(['foo']));

        $machine = new StateMachine();
        $machine->setStates(
            [
                $initial
            ]
        );

        $machine->changeToState($payload, 'foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testChangeToStateWithFailedConditions()
    {
        $payload = $this->getMock('\StateMachine\StatefulInterface');
        $payload->expects($this->any())->method('getState')->will($this->returnValue('initial'));

        $initial = $this->getMock('\StateMachine\StateInterface');
        $initial->expects($this->any())->method('getName')->will($this->returnValue('initial'));
        $initial->expects($this->any())->method('getTargetStates')->will($this->returnValue(['foo']));

        $target = $this->getMock('\StateMachine\StateInterface');
        $target->expects($this->any())->method('getName')->will($this->returnValue('foo'));
        $target->expects($this->any())->method('check')->will($this->returnValue(false));

        $machine = new StateMachine();
        $machine->setStates(
            [
                $initial,
                $target
            ]
        );

        $machine->changeToState($payload, 'foo');
    }

    public function testChangeToState()
    {
        $payload = $this->getMock('\StateMachine\StatefulInterface');
        $payload->expects($this->any())->method('getState')->will($this->returnValue('initial'));
        $payload->expects($this->exactly(1))->method('setState')->with('foo');

        $initial = $this->getMock('\StateMachine\StateInterface');
        $initial->expects($this->any())->method('getName')->will($this->returnValue('initial'));
        $initial->expects($this->any())->method('getTargetStates')->will($this->returnValue(['foo']));

        $target = $this->getMock('\StateMachine\StateInterface');
        $target->expects($this->any())->method('getName')->will($this->returnValue('foo'));
        $target->expects($this->any())->method('check')->will($this->returnValue(true));
        $target->expects($this->any())->method('execute')->will($this->returnValue($payload));

        $machine = new StateMachine();
        $machine->setStates(
            [
                $initial,
                $target
            ]
        );

        $this->assertEquals($payload, $machine->changeToState($payload, 'foo'));
    }
}
 