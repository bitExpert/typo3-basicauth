<?php

namespace bitExpert\Basicauth\Service;

/**
 * Class UserService
 * @package bitExpert\Basicauth\Service
 */
class BasicAuthService
{
    const FILE_NAME = 'BASIC_AUTH_ENABLED';

    /**
     * @var DatabaseConnection
     */
    protected $dbConnection;

    /**
     * BasicAuthService constructor.
     */
    public function __construct()
    {
        $this->dbConnection = $GLOBALS['TYPO3_DB'];
    }

    /**
     * enables/disables the basic auth check
     * @param bool $enabled
     */
    public function setEnabled($enabled = true)
    {
        if ($enabled) {
            $user = $GLOBALS['BE_USER']->user['username'];
            file_put_contents($this->getBasicAuthFile(), sprintf('Enabled by %s at %s', $user, date('Y-m-d H:i:s')));
        } else {
            unlink($this->getBasicAuthFile());
        }
    }

    /**
     * checks if basic auth is currently enabled or not
     * @return bool
     */
    public function isEnabled()
    {
        return file_exists($this->getBasicAuthFile());
    }

    /**
     * @return string
     */
    public function getInfo()
    {
        return file_exists($this->getBasicAuthFile()) ? file_get_contents($this->getBasicAuthFile()) : '';
    }

    /**
     * @return string
     */
    protected function getBasicAuthFile()
    {
        return \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName(PATH_site . 'typo3conf/' . self::FILE_NAME);
    }

    /**
     * validates backend users
     */
    public function runBasicAuthValidation()
    {

        if (!$this->isEnabled()) {
            return;
        }
        $users = [];
        $results = $this->dbConnection->exec_SELECTgetRows('username,password', 'be_users', 'disable=0 AND deleted=0');
        foreach ($results as $result) {
            $users[$result['username']] = $result['password'];
        }
        $this->basicAuth($users);
    }

    /**
     * enables basic authentication check
     * @param array $users ["username" => "passphrase"]
     */
    protected function basicAuth(array $users)
    {
        if (empty($users)) {
            $this->showUnauthorized();
            return;
        }

        $user = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];

        $saltedPassword = $users[$user];

        if (!$this->checkSaltedPassword($password, $saltedPassword)) {
            $this->showUnauthorized();
        }
    }

    /**
     * shows an unauthorized message and cancels script execution
     */
    protected function showUnauthorized()
    {
        header('WWW-Authenticate: Basic realm="Not authorized"');
        header('HTTP/1.0 401 Unauthorized');
        die("Not authorized");
    }

    /**
     * @param $password
     * @param $saltedPassword
     * @return bool
     */
    protected function checkSaltedPassword($password, $saltedPassword)
    {
        $objSalt = \TYPO3\CMS\Saltedpasswords\Salt\SaltFactory::getSaltingInstance($saltedPassword, 'BE');
        if (is_object($objSalt)) {
            return $objSalt->checkPassword($password, $saltedPassword);
        }
        return false;
    }
}
