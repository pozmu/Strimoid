<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => array(

        'mongodb' => array(
            'driver'   => 'mongodb',
            'host'     => $_SERVER['MONGO_ADDR'],
            'port'     => $_SERVER['MONGO_PORT'],
            'username' => $_SERVER['MONGO_USER'],
            'password' => $_SERVER['MONGO_PASS'],
            'database' => $_SERVER['MONGO_DB']
        ),

        'stats' => array(
            'driver'   => 'sqlite',
            'database' => __DIR__.'/../database/stats.sqlite',
            'prefix'   => '',
        ),

    ),

);
