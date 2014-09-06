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
 * Interface for state subject
  *
 * @package StateMachine
 */
interface StatefulInterface {

    /**
     * Returns current subject state
     *
     * @return string
     */
    public function getState();

    /**
     * Sets subject state
     *
     * @param string $name
     *
     * @return $this
     */
    public function setState($name);
} 