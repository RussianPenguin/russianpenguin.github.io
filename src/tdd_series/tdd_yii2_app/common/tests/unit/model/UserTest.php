<?php

namespace common\tests\unit\model;

use Codeception\Test\Unit;
use common\models\User;

class UserTest extends Unit
{
    public function testValidateEmptyFields()
    {
        $user = new User();
        $this->assertFalse($user->validate(), 'Validate username, password, email (empty)');
        $this->assertArrayHasKey('password_hash', $user->getErrors(), 'Check for password errors');
        $this->assertArrayHasKey('email', $user->getErrors(), 'Check for email errors');
        $this->assertArrayHasKey('username', $user->getErrors(), 'Check for username errors');
    }
    public function testEmailFormat()
    {
        $user = new User(['email' => 'sdfsdfsdfsdf', 'username' => 'username', 'password' => 123]);
        $this->assertFalse($user->validate(), 'Validate username, password, email (incorrect)');
        $this->assertArrayNotHasKey('password', $user->getErrors(), 'Check for password errors');
        $this->assertArrayHasKey('email', $user->getErrors(), 'Check for email errors');
        $this->assertArrayNotHasKey('username', $user->getErrors(), 'Check for username errors');
    }
    public function testAddUser()
    {
        $user = new User(['email' => 'test@test.test', 'username' => 'admin', 'password' => 'admin']);
        $user->generateAuthKey();
        $this->assertTrue($user->validate());
        $user->save();
        $this->assertTrue(1 == User::find()->where(['email' => 'test@test.test'])->count());
    }
    public function testPasswordHashing()
    {
        $password = 'test';
        $user = new User(['email' => 'test@test.test', 'username' => 'test', 'password' => $password]);
        $this->assertTrue(\Yii::$app->security->validatePassword($password, $user->password_hash));
    }
    public function testEmptyPassword()
    {
        $user = new User(['email' => 'test@test.test', 'username' => 'username', 'password' => '']);
        $this->assertFalse($user->validate());
        $this->assertArrayNotHasKey('username', $user->getErrors());
        $this->assertArrayNotHasKey('email', $user->getErrors());
        $this->assertArrayHasKey('password_hash', $user->getErrors());
    }
    public function testEmptyPasswordDirectSet()
    {
        $user = new User(['email' => 'test@test.test', 'username' => 'username']);
        $user->password = '';
        $this->assertFalse($user->validate());
        $this->assertArrayNotHasKey('username', $user->getErrors());
        $this->assertArrayNotHasKey('email', $user->getErrors());
        $this->assertArrayHasKey('password_hash', $user->getErrors());
    }
    public function testPasswordValidation()
    {
        $password = \Yii::$app->security->generateRandomString(32);
        $user = new User(['email' => 'test@test.test', 'username' => 'test', 'password' => $password]);
        $this->assertTrue($user->validatePassword($password));
    }
    public function testPasswordValidationAfterSave()
    {
        $password = 'SOME_FAKE_PASSWORD';
        $email = 'test@test.test';
        $user = new User(['email' => $email, 'username' => 'test', 'password' => $password]);
        $user->generateAuthKey();
        $this->assertTrue($user->validatePassword($password));
        $user->save();
        $user2 = User::findByEmail($email);
        $this->assertTrue($user2->validatePassword($password));
    }
    public function setUp()
    {
        \Yii::$app->db->createCommand()->truncateTable('{{%user}}')->execute();
    }
}
