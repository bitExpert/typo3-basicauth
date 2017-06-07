<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
    'BitExpert.' . $_EXTKEY,
    'tools',
    'basicauth',
    '',
    [
        'Admin' => 'index,enableBasicAuth,disableBasicAuth',
    ],
    [
        'access' => 'user,group',
        'icon' => 'EXT:' . $_EXTKEY . '/Resources/Public/Images/moduleIcon.svg',
        'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml',
    ]
);
