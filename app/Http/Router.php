<?php
/**
 * Created by PhpStorm.
 * User: malitha
 * Date: 8/22/2017
 * Time: 4:57 PM
 */

namespace Http\Router;


class Router
{

    /**
     * @return bool|string
     */
    public static function getCurrentUri()
    {
        $base_path = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
        $uri       = substr($_SERVER['REQUEST_URI'], strlen($base_path));
        if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
        $uri = trim($uri, '/');
        return $uri;
    }

    /**
     * @return array or error response
     */
    public static function validate()
    {
        $base_url  = self::getCurrentUri();
        $routesMap = self::getAvailableRoutes();

        $routeAvailable = array_map(function ($item) use ($base_url) {
            $route = $item['version'] . '/' . $item['route'];
            if (strcasecmp($route, $base_url) == 0) {
                return $item;
            }
        }, $routesMap);

        if ($routeAvailable[0]) {

            if ($_SERVER['REQUEST_METHOD'] === $routeAvailable[0]['method']) {
                return [
                    'status' => true,
                    'route'  => $routeAvailable[0]
                ];
            } else {

            }

            // return json error response method 405
        }

        //return json error with 400

    }

    /**
     * @return mixed
     */
    public static function getAvailableRoutes()
    {
        $routesMap = include 'routes.php';
        return $routesMap;
    }
}