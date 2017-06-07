<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

//Hook for Processing Pre Requests
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/index_ts.php']['preprocessRequest'][$_EXTKEY] =
    'bitExpert\Basicauth\Hooks\Request->preprocessRequest';
