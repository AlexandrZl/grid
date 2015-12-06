<?php

class Route
{
    private $route;

    public function __construct()
    {
        $this->route = explode('/', $_SERVER['REQUEST_URI']);
    }

    public function getRoute()
    {
        foreach ($this->route as $key => $value) {
            $value = explode("?", $value)[0];
            if ($value) return $value;
        }

        return false;
    }
}