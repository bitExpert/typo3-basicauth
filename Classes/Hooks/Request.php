<?php

namespace BitExpert\Basicauth\Hooks;

use BitExpert\Basicauth\Service\BasicAuthService;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class Request
 * @package BitExpert\Basicauth\Hooks
 */
class Request implements SingletonInterface
{

    /**
     * @var \BitExpert\Basicauth\Service\BasicAuthService
     */
    protected $basicAuthService;

    /**
     * @param $params
     * @param $_
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function preprocessRequest($params, $_)
    {
        $this->basicAuthService = GeneralUtility::makeInstance(BasicAuthService::class);
        $this->basicAuthService->runBasicAuthValidation();
    }
}
