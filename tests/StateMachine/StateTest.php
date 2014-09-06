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


class StateTest extends \PHPUnit_Framework_TestCase
{

    public function testName()
    {
        $state = new State('foo');
        $this->assertEquals('foo', $state->getName());
    }

    public function testConditions()
    {
        $condition = function () { };

        $state = new State('foo');
        $state->addCondition($condition);
        $this->assertEquals([$condition], $state->getConditions());
    }

    public function testCheckSuccess()
    {
        $payload = $this->getMock('\StateMachine\StatefulInterface');

        $state = new State('foo');
        $state->addCondition(function () { return true; });

        $this->assertTrue($state->check($payload));
    }

    public function testCheckFail()
    {
        $payload = $this->getMock('\StateMachine\StatefulInterface');

        $state = new State('foo');
        $state->addCondition(function () { return false; });

        $this->assertFalse($state->check($payload));
    }

    public function testCommands()
    {
        $command = function () { };

        $state = new State('foo');
        $state->addCommand($command);
        $this->assertEquals([$command], $state->getCommands());
    }

    public function testExecute()
    {
        $subject = (object) ['i' => 0];

        $payload = $this->getMock('\StateMachine\StatefulInterface');
        $payload->expects($this->exactly(3))->method('getSubject')->will($this->returnValue($subject));

        $command = function(StatefulInterface $payload) {
            $payload->getSubject()->i += 1;
            return $payload;
        };

        $state = new State('foo');
        $state->addCommand($command);
        $state->addCommand($command);

        $this->assertEquals(2, $state->execute($payload)->getSubject()->i);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Execute throws exception
     */
    public function testExecuteWithException()
    {
        $payload = $this->getMock('\StateMachine\StatefulInterface');

        $state = new State('foo');
        $state->addCommand(function () { throw new \Exception('Execute throws exception'); });

        $this->assertTrue($state->execute($payload));
    }

    public function testTargetState()
    {
        $state = new State('foo');
        $state->addTargetState('bar');
        $this->assertEquals(['bar'], $state->getTargetStates());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Target state can not be same state
     */
    public function testTargetStateIsSameState()
    {
        $state = new State('foo');
        $state->addTargetState('foo');
    }
}
 