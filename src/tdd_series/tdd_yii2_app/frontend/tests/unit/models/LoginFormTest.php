<?php

namespace frontend\tests\unit;

use Codeception\Test\Unit;
use common\models\LoginForm;
use yii\web\User;
use common\fixtures\User as UserFixture;

class LoginFormTest extends Unit
{
    protected const USER_EMAIL = 'test@test.test';
    protected const USER_PASSWORD = 'test';
    protected const USER_PASSWORD_HASH = '$2y$13$PP1EDCr7ujdhTxZT2DV96uM8e2rcdXHY1xAQINCIiB0gOck/VBwN6';

    protected static $_storedEntities = [
        'user' => null,
    ];

    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;


    /**
     * Use user fixtures for database testing
     */
    public function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ]);
    }

    public function testValidationIsTrue()
    {
        $loginForm = new LoginForm([
            'email' => self::USER_EMAIL,
            'password' => 'test',
        ]);
        $this->assertTrue($loginForm->validate());
    }

    public function testAuthorizationCall()
    {
        $mock = $this->getMockBuilder(User::class)
            ->setMethods(['login'])
            ->disableOriginalConstructor()
            ->getMock();
        $mock->method('login')->withAnyParameters()->willReturn(true);
        \yii::$app->set('user', $mock);
        $loginForm = new LoginForm([
            'email' => self::USER_EMAIL,
            'password' => self::USER_PASSWORD,
        ]);
        $this->assertTrue($loginForm->login());
    }

    public function testTestUserExists()
    {
        $user = \common\models\User::findByEmail(self::USER_EMAIL);
        $this->assertNotEmpty($user);
    }

    public function testTest1UserNotExists()
    {
        $user = \common\models\User::findByEmail('test1@test.test');
        $this->assertEmpty($user);
    }

    public function testTest2UserExists()
    {
        $user = \common\models\User::findByEmail('test2@test.test');
        $this->assertNotEmpty($user);
    }

    public function testTestUserLogin()
    {
        $mock = $this->getMockBuilder(User::class)
            ->setMethods(['login'])
            ->disableOriginalConstructor()
            ->getMock();
        $mock->method('login')->withAnyParameters()->willReturn(true);
        \Yii::$app->set('user', $mock);
        $loginForm = new LoginForm();
        $loginForm->load(['LoginForm' => ['email' => static::USER_EMAIL, 'password' => static::USER_PASSWORD]]);
        $this->assertTrue($loginForm->login());
    }
}
