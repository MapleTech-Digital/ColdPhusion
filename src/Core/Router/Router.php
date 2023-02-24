<?php

namespace Core\Router;

use Core\DevTools\VarDumper;
use Core\Logger\Logger;

class Router
{
    public static ?Router $instance = null;

    private array $route_table = [];

    public function __construct()
    {

    }

    public function load($route_table) {
        Logger::Debug("Loading Route Table", ["table" => $route_table]);
        $this->route_table = $route_table;
    }

    public function match($path_info)
    {
        $matched_route = null;
        foreach($this->route_table as $route_name => $potential_route) {

            $is_wildcard = false;
            if(str_contains($potential_route['route'], '*')) {
                $is_wildcard = true;
                $potential_route['route'] = rtrim($potential_route['route'], '*');
            }

            // break the route down by forward slash
            $route_chunks = explode('/', $potential_route['route']);
            $path_chunks = explode('/', $path_info);

            if(!$is_wildcard && count($route_chunks) !== count($path_chunks)) {
                // if not a wildcard route and mismatch on chunk count, don't consider further
                continue;
            }

            if($is_wildcard) {
                // if wildcard, combine all path chunks after last route chunk index
                $combined = implode('/', array_slice($path_chunks, count($route_chunks) - 1, count($path_chunks)));
                $path_chunks = array_slice($path_chunks, 0, count($route_chunks) - 1);
                $path_chunks[] = $combined;
            }

            $found = false;
            $parameters = [];
            foreach($path_chunks as $index => $pchunk) {

                $rchunk = $route_chunks[$index];

                if($pchunk === $rchunk) {
                    $found = true;
                    continue;
                }

                if(str_starts_with($rchunk, ':')) {
                    $found = true;
                    $parameters[substr($rchunk, 1)] = $pchunk;
                    continue;
                }

                $found = false;
                break;
            }

            if(!$found) continue;

            $matched_controller_chunks = explode('::', $potential_route['controller']);

            $matched_route = [
                'name' => $route_name,
                'controller_raw' => $potential_route['controller'],
                'controller' => $matched_controller_chunks[0],
                'action' => $matched_controller_chunks[1],
                'parameters' => $parameters
            ];

            Logger::Debug("Potential matched route", [
                'url' => $path_info,
                'match' => $matched_route
            ]);
        }

        Logger::Info("Matched route", $matched_route);
        return $matched_route;
    }

    public static function Get($route_table = null)
    {
        if(!self::$instance) {
            self::$instance = new self();
        }

        if($route_table) {
            self::$instance->load($route_table);
        }

        return self::$instance;
    }
}
