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
 * StateMachine, moves subject from one state to another
 *
 * @package StateMachine
 */
class StateMachine implements StateMachineInterface
{
    protected $states = [];

    /**
     * Adds state to machine
     *
     * @param StateInterface $state
     *
     * @return $this
     */
    public function addState(StateInterface $state)
    {
        $this->states[$state->getName()] = $state;

        return $this;
    }

    /**
     * Returns true if machine has state
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasState($name)
    {
        return isset($this->states[$name]);
    }

    /**
     * Returns state with given name
     *
     * @param string $name
     *
     * @return StateInterface
     * @throws \InvalidArgumentException
     */
    public function getState($name)
    {
        if (!$this->hasState($name)) {
            throw new \InvalidArgumentException(sprintf('Invalid state name or missing state with name %s', $name));
        }

        return $this->states[$name];
    }

    /**
     * Sets states to machine
     *
     * @param array|StateInterface[] $states
     *
     * @return $this
     */
    public function setStates($states)
    {
        foreach ((array) $states as $state) {
            $this->addState($state);
        }

        return $this;
    }

    /**
     * Returns all registered states
     *
     * @return array|StateInterface[]
     */
    public function getStates()
    {
        return $this->states;
    }

    /**
     * Moves subject to next state, if available
     *
     * @param StatefulInterface $subject
     *
     * @return StatefulInterface
     */
    public function changeState(StatefulInterface $subject)
    {
        $current = $this->getState($subject->getState());
        foreach ($current->getTargetStates() as $target) {
            $state = $this->getState($target);

            if ($state->check($subject)) {
                $subject = $state->execute($subject);
                $subject->setState($state->getName());
                break;
            }
        }

        return $subject;
    }

    /**
     * Moves subject to state
     *
     * @param StatefulInterface $subject
     * @param string            $target
     *
     * @return StatefulInterface
     * @throws \InvalidArgumentException
     */
    public function changeToState(StatefulInterface $subject, $target)
    {
        $state = $this->getState($target);
        if (!$state->check($subject)) {
            throw new \InvalidArgumentException(sprintf('Subject failed conditions to change state to %s', $state->getName()));
        }

        $subject = $state->execute($subject);
        $subject->setState($state->getName());

        return $subject;
    }

}