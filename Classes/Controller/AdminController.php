<?php

namespace BitExpert\Basicauth\Controller;

use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class AdminController
 * @package BitExpert\Basicauth\Controller
 */
class AdminController extends ActionController
{

    /**
     * @var \BitExpert\Basicauth\Service\BasicAuthService
     * @inject
     */
    protected $basicAuthService;

    /**
     * @var int
     */
    protected $pageId;

    /**
     * @var DatabaseConnection
     */
    protected $dbConnection;


    /**
     * @inheritdoc
     */
    protected function initializeAction()
    {
        parent::initializeAction();

        $this->dbConnection = $GLOBALS['TYPO3_DB'];

        if ($GLOBALS['TSFE'] === null) {
            $this->pageId = (int)\TYPO3\CMS\Core\Utility\GeneralUtility::_GP('id');
        } else {
            $this->pageId = $GLOBALS['TSFE']->id;
        }
    }

    /**
     * @return string
     */
    public function indexAction()
    {
        $this->view->assignMultiple([
            'basicAuthEnabled' => (int)$this->basicAuthService->isEnabled(),
            'basicAuthInfo' => $this->basicAuthService->getInfo()
        ]);

        return $this->view->render();
    }

    /**
     * enables basic authentication
     */
    public function enableBasicAuthAction()
    {
        $this->basicAuthService->setEnabled(true);
        $this->forward('index');
    }

    /**
     * disables basic authentication
     */
    public function disableBasicAuthAction()
    {
        $this->basicAuthService->setEnabled(false);
        $this->forward('index');
    }
}
