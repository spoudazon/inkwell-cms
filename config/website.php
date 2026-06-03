<?php

declare(strict_types=1);

return [
    'name' => 'Inkwell CMS',
    'theme' => 'default',
    'url' => 'http://localhost:8080',
    'timezone' => 'Europe/Warsaw',
    'locale' => 'en-US',
    'domain' => 'localhost',
    'menu' => [
        'home' => [
            'title' => 'Home',
            'url' => '/',
        ],
        'about' => [
            'title' => 'About',
            'url' => '/page/about',
        ],
        'contact' => [
            'title' => 'Contact',
            'url' => '/page/contact',
        ],
    ],
];
