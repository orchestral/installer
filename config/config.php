<?php

return [

    /*
    |----------------------------------------------------------------------
    | Listeners for Installation
    |----------------------------------------------------------------------
    */

    'installers' => [
        'paths' => [
            \base_path('orchestra'),
            \database_path('orchestra'),
        ],
    ],

];
