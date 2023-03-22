<?php

use Laravel\Lumen\Routing\Router;

/** 
 * @var Router $router
 */
$router->group(['prefix' => '/api'], function() use ($router) {
    $router->post('/services/dsp', 'Api\Customer\Services\Dsp\DspController@verify');
});