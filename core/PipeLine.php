<?php


namespace core;


class PipeLine
{
    protected $classes = [];
    protected $handleMethod = 'handle';

    public function create()
    {
        return clone $this;
    }

    public function setHandleMethod($method)
    {
        $this->handleMethod = $method;
        return $this;
    }

    public function setClass($class) {
        $this->classes = $class;
        return $this;
    }

    public function run(\Closure $initial)
    {
        return array_reduce(array_reverse($this->classes), function($res, $currClass) {
            return function ($request) use ($res, $currClass) {
                return (new $currClass)->{$this->handleMethod}($request, $res);
            };
        }, $initial);
    }
}