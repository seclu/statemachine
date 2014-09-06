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
 * Interface StateInterface implemented by all states
 *
 * @package StateMachine
 */
interface StateInterface
{

    /**
     * Returns state name
     *
     * @return string
     */
    public function getName();

    /**
     * Adds condition to state
     *
     * @param callable $condition
     *
     * @return $this
     */
    public function addCondition($condition);

    /**
     * Returns array containing all state conditions
     *
     * @return array|callable[]
     */
    public function getConditions();

    /**
     * Returns true if all conditions are meet, false otherwise
     *
     * @param StatefulInterface $subject
     *
     * @return bool
     */
    public function check(StatefulInterface $subject);

    /**
     * Adds command to be executed by state
     *
     * @param callable $command
     *
     * @return $this
     */
    public function addCommand($command);

    /**
     * Returns array of all commands in state
     *
     * @return array|callable[]
     */
    public function getCommands();

    /**
     * Executes all commands on subject and returns it
     *
     * @param StatefulInterface $subject
     *
     * @return StatefulInterface
     */
    public function execute(StatefulInterface $subject);

    /**
     * Add target state
     *
     * @param string $state
     *
     * @return $this
     */
    public function addTargetState($state);

    /**
     * Returns true if state is target state
     *
     * @param string $state
     *
     * @return bool
     */
    public function hasTargetState($state);

    /**
     * Returns collection of all possible target state names
     *
     * @return array
     */
    public function getTargetStates();
}