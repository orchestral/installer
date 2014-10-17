<?php

use Illuminate\Support\Facades\Route;
use Orchestra\Support\Facades\Foundation;

Foundation::namespaced('Orchestra\Installation\Routing', function () {
    Route::group(['prefix' => 'install'], function ($router) {
        // Route to installation.
        $router->get('/', 'InstallerController@index');
        $router->get('create', 'InstallerController@create');
        $router->post('create', 'InstallerController@store');
        $router->get('done', 'InstallerController@done');
        $router->get('prepare', 'InstallerController@prepare');
    });
});
