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
 * Basic state class
 *
 * @package StateMachine
 */
class State implements StateInterface
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array|callable[]
     */
    protected $conditions;

    /**
     * @var array|callable[]
     */
    protected $commands;

    /**
     * @var array
     */
    protected $targetStates;

    /**
     * Constructor
     *
     * @param string           $name
     * @param array|callable[] $conditions
     * @param array|callable[] $commands
     * @param array $targetStates

     */
    public function __construct($name, $conditions = [], $commands = [], $targetStates = [])
    {
        $this->name = (string) $name;
        $this->conditions = (array) $conditions;
        $this->commands = (array) $commands;
        $this->targetStates = (array) $targetStates;
    }

    /**
     * Returns state name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Adds condition to state
     *
     * @param callable $condition
     *
     * @return $this
     */
    public function addCondition($condition)
    {
        $this->conditions[] = $condition;

        return $this;
    }

    /**
     * Returns array containing all state conditions
     *
     * @return array|callable[]
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * Returns true if all conditions are meet, false otherwise
     *
     * @param StatefulInterface $subject
     *
     * @return bool
     */
    public function check(StatefulInterface $subject)
    {
        foreach ($this->conditions as $condition) {
            if (!$condition($subject)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Adds command to be executed by state
     *
     * @param callable $command
     *
     * @return $this
     */
    public function addCommand($command)
    {
        $this->commands[] = $command;

        return $this;
    }

    /**
     * Returns array of all commands in state
     *
     * @return array|callable[]
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * Executes all commands on subject and returns it
     *
     * @param StatefulInterface $subject
     *
     * @return mixed
     */
    public function execute(StatefulInterface $subject)
    {
        foreach ($this->commands as $command) {
            $subject = $command($subject);
        }

        return $subject;
    }

    /**
     * Add target state
     *
     * @param string $state
     *
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function addTargetState($state)
    {
        if ($this->name == $state) {
            throw new \InvalidArgumentException('Target state can not be same state');
        }

        $this->targetStates[] = (string) $state;
    }

    /**
     * Returns true if state is target state
     *
     * @param string $state
     *
     * @return bool
     */
    public function hasTargetState($state)
    {
        return in_array($state, $this->targetStates);
    }

    /**
     * Returns collection of all possible target state names
     *
     * @return array
     */
    public function getTargetStates()
    {
        return $this->targetStates;
    }
}