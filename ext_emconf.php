<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Basic Authentication',
    'description' => 'Basic authentication (htaccess) support for frontend pages',
    'category' => 'plugin',
    'author' => 'Nico Blum',
    'author_email' => 'n.blum@bitexpert.de',
    'author_company' => 'bitExpert AG',
    'shy' => '',
    'priority' => '',
    'module' => '',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'modify_tables' => '',
    'clearCacheOnLoad' => 0,
    'lockType' => '',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'php' => '5.6',
            'extbase' => '1.4.0',
            'typo3' => '7.6.2'
        ],
        'conflicts' => [],
        'suggests' => []
    ],
    'autoload' => [
        'psr-4' => [
            'BitExpert\\Basicauth\\' => 'Classes'
        ]
    ]
];
