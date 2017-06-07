<?php

namespace BitExpert\Tests\Service;

use BitExpert\Basicauth\Service\BasicAuthService;
use PHPUnit\Framework\TestCase;

/**
 * Class BasicAuthUnitTest
 * @package BitExpert\Tests\Service
 */
class BasicAuthUnitTest extends TestCase
{

    /**
     * @test
     */
    public function shouldReturnNotEnabled()
    {
        $GLOBALS['TYPO3_DB'] = null;

        $basicAuthService = $this->getMockBuilder(BasicAuthService::class)
            ->setMethods(['getBasicAuthFile'])
            ->getMock();

        $basicAuthService->expects($this->once())
            ->method('getBasicAuthFile')
            ->willReturn('test/data/BASIC_AUTH_NOT_ENABLED');


        /* @var BasicAuthService $basicAuthService */
        $this->assertFalse($basicAuthService->isEnabled());
    }

    /**
     * @test
     */
    public function shouldReturnEnabled()
    {
        $GLOBALS['TYPO3_DB'] = null;

        $basicAuthService = $this->getMockBuilder(BasicAuthService::class)
            ->setMethods(['getBasicAuthFile'])
            ->getMock();

        $basicAuthService->expects($this->once())
            ->method('getBasicAuthFile')
            ->willReturn(__DIR__ . '/../../tests/data/BASIC_AUTH_ENABLED');


        /* @var BasicAuthService $basicAuthService */
        $this->assertTrue($basicAuthService->isEnabled());
    }

    /**
     * @test
     */
    public function shouldEnableAndDisableBasicAuth()
    {
        $file = __DIR__ . '/../../build/test-file-' . time();
        $GLOBALS['TYPO3_DB'] = null;
        $GLOBALS['BE_USER'] = (object)[
            'user' => [
                'username' => 'testuser'
            ]
        ];

        $basicAuthService = $this->getMockBuilder(BasicAuthService::class)
            ->setMethods(['getBasicAuthFile'])
            ->getMock();

        $basicAuthService->expects($this->any())
            ->method('getBasicAuthFile')
            ->willReturn($file);

        /* @var BasicAuthService $basicAuthService */
        $this->assertFalse($basicAuthService->isEnabled());

        $basicAuthService->setEnabled();

        $this->assertTrue($basicAuthService->isEnabled());

        $basicAuthService->setEnabled(false);
        $this->assertFalse($basicAuthService->isEnabled());
    }

    /**
     * @test
     */
    public function shouldContainInfoText()
    {
        $file = __DIR__ . '/../../build/test-file-' . time();
        $GLOBALS['TYPO3_DB'] = null;
        $GLOBALS['BE_USER'] = (object)[
            'user' => [
                'username' => 'testuser'
            ]
        ];

        $basicAuthService = $this->getMockBuilder(BasicAuthService::class)
            ->setMethods(['getBasicAuthFile'])
            ->getMock();

        $basicAuthService->expects($this->any())
            ->method('getBasicAuthFile')
            ->willReturn($file);

        /* @var BasicAuthService $basicAuthService */
        $this->assertFalse($basicAuthService->isEnabled());

        $basicAuthService->setEnabled();
        $info = $basicAuthService->getInfo();

        $this->assertStringStartsWith('Enabled by testuser', $info);

        unlink($file);
    }

    /**
     * @test
     */
    public function shouldRunBasicAuthValidationWithBackendUsers()
    {
        $users = [
            [
                'username' => 'testuser1',
                'password' => 'testpassword1'
            ]
        ];

        $basicAuthService = $this->getBasicAuthService($users);

        $basicAuthService->expects($this->any())
            ->method('isEnabled')
            ->willReturn(true);

        $basicAuthService->expects($this->never())
            ->method('showUnauthorized');

        $this->runAuthorization($basicAuthService, 'testuser1', 'testpassword1');
    }

    /**
     * @test
     */
    public function shouldShowUnauthorizedMessageWithWrongCredentials()
    {
        $users = [
            [
                'username' => 'testuser1',
                'password' => 'testpassword1'
            ]
        ];

        $basicAuthService = $this->getBasicAuthService($users);

        $basicAuthService->expects($this->any())
            ->method('isEnabled')
            ->willReturn(true);

        $basicAuthService->expects($this->once())
            ->method('showUnauthorized');

        $this->runAuthorization($basicAuthService, 'testuser1', 'loremipsum');
    }

    /**
     * @test
     */
    public function shouldShowUnauthorizedMessageWithEmptyUsers()
    {
        $users = [
        ];

        $basicAuthService = $this->getBasicAuthService($users);

        $basicAuthService->expects($this->any())
            ->method('isEnabled')
            ->willReturn(true);

        $basicAuthService->expects($this->once())
            ->method('showUnauthorized');

        $this->runAuthorization($basicAuthService, 'testuser1', 'loremipsum');
    }

    protected function runAuthorization($basicAuthService, $user, $password)
    {

        $_SERVER['PHP_AUTH_USER'] = $user;
        $_SERVER['PHP_AUTH_PW'] = $password;

        $basicAuthService->expects($this->any())
            ->method('checkSaltedPassword')
            ->will($this->returnCallback(function () {
                $args = func_get_args();
                return $args[0] === $args[1];
            }));

        /* @var BasicAuthService $basicAuthService */
        $basicAuthService->runBasicAuthValidation();
    }

    /**
     * @param array $systemUsers
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getBasicAuthService(array $systemUsers)
    {
        $dbConnection = $this->getMockBuilder('DbConnection')
            ->disableOriginalClone()
            ->setMethods(['exec_SELECTgetRows'])
            ->getMock();

        $dbConnection->expects($this->once())
            ->method('exec_SELECTgetRows')
            ->willReturn($systemUsers);

        $GLOBALS['TYPO3_DB'] = $dbConnection;

        return $this->getMockBuilder(BasicAuthService::class)
            ->setMethods(['isEnabled', 'showUnauthorized', 'checkSaltedPassword'])
            ->getMock();
    }
}
