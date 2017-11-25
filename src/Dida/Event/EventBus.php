<?php
/**
 * Dida Framework  -- A Rapid Development Framework
 * Copyright (c) Zeupin LLC. (http://zeupin.com)
 *
 * Licensed under The MIT License.
 * Redistributions of files must retain the above copyright notice.
 */

namespace Dida\Event;

final class EventBus
{
    const VERSION = '20171124';

    protected $events = [];

    protected $hooks = [];


    public function add($event)
    {
        if (!isset($this->events[$event])) {
            $this->events[$event] = true;
            return true;
        }
    }


    public function remove($event)
    {
        unset($this->events[$event]);
        unset($this->hooks[$event]);
    }


    public function has($event)
    {
        return isset($this->events[$event]);
    }


    public function hook($event, $callback, array $parameters = [], $id = null)
    {
        if ($this->has($event)) {
            if ($id === null) {
                $this->hooks[$event][] = [$callback, $parameters];
            } else {
                $this->hooks[$event][$id] = [$callback, $parameters];
            }
        } else {
            throw new EventException($event, EventException::EVENT_NOT_FOUND);
        }

        return $this;
    }


    public function unhook($event, $id = null)
    {
        if ($id === null) {
            unset($this->hooks[$event]);
        } else {
            unset($this->hooks[$event][$id]);
        }
        return $this;
    }


    public function trigger($event)
    {
        if (array_key_exists($event, $this->hooks)) {
            foreach ($hooks as $hook) {
                list($callback, $parameters) = $hook;
                if (call_user_func_array($callback, $parameters) === false) {
                    break;
                }
            }
        }
    }
}
