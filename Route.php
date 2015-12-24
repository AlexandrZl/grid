<?php

class Route
{
    private $route;

    public function __construct()
    {
        $this->route = explode('/', $_SERVER['REQUEST_URI'])[1];
    }

    public function getRoute()
    {
        return parse_url($this->route)['path'];
    }

    public function getParams()
    {
        return $this->parseParams(parse_url($this->route)['query']);
    }

    private function parseParams($_params)
    {
        parse_str($_params, $output);
        return $output;
    }

}