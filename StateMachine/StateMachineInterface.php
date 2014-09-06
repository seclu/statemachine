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

/**
 * State machine interface
 *
 * @package StateMachine
 */
interface StateMachineInterface
{

    /**
     * Adds state to machine
     *
     * @param StateInterface $state
     *
     * @return $this
     */
    public function addState(StateInterface $state);

    /**
     * Returns true if machine has state
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasState($name);

    /**
     * Returns state with given name
     *
     * @param string $name
     *
     * @return StateInterface
     */
    public function getState($name);

    /**
     * Sets states to machine
     *
     * @param array|StateInterface[] $states
     *
     * @return $this
     */
    public function setStates($states);

    /**
     * Returns all registered states
     *
     * @return array|StateInterface[]
     */
    public function getStates();

    /**
     * Moves subject to next state, if available
     *
     * @param StatefulInterface $subject
     *
     * @return StatefulInterface
     */
    public function changeState(StatefulInterface $subject);

    /**
     * Moves subject to state
     *
     * @param StatefulInterface $subject
     * @param string            $target
     *
     * @return StatefulInterface
     */
    public function changeToState(StatefulInterface $subject, $target);
} 