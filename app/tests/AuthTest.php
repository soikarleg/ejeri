<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../classes/Auth.php';

class AuthTest extends TestCase
{
    private $userModel;
    private $compte_id = 1; // À adapter selon la base de test

    protected function setUp(): void
    {
        $this->userModel = new UserModel($this->compte_id);
    }

    public function testUserCreationAndLogin()
    {
        $email = 'testuser@example.com';
        // Nettoyage : supprimer l'utilisateur s'il existe déjà
        $this->userModel->deleteByEmail($email);
        $password = password_hash('Test1234!', PASSWORD_ARGON2ID);
        $data = [
            'email' => $email,
            'password' => $password,
            'nom' => 'Test',
            'prenom' => 'User',
            'role' => 'gerant',
            'is_active' => 1,
            'token_validation' => null
        ];
        $this->userModel->create($data);
        $user = $this->userModel->findByEmail($email);
        $this->assertTrue(!empty($user));
        $this->assertTrue(password_verify('Test1234!', $user['password']));
    }

    public function testSetAndFindResetToken()
    {
        date_default_timezone_set('Europe/Paris');
        $email = 'testuser@example.com';
        $this->userModel->deleteByEmail($email);
        $password = password_hash('Test1234!', PASSWORD_ARGON2ID);
        $data = [
            'email' => $email,
            'password' => $password,
            'nom' => 'Test',
            'prenom' => 'User',
            'role' => 'gerant',
            'is_active' => 1,
            'token_validation' => null
        ];
        $this->userModel->create($data);
        $token = bin2hex(random_bytes(32));
        $expire = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $this->userModel->setResetToken($email, $token, $expire);
        $user = $this->userModel->findByResetToken($token);
        $this->assertNotEmpty($user, 'Reset token not found or expired');
        $this->assertSame($token, $user['reset_token'], 'Reset token does not match');
    }

    public function testResetPassword()
    {
        $email = 'testuser@example.com';
        $user = $this->userModel->findByEmail($email);
        $newPassword = password_hash('NewPass123!', PASSWORD_ARGON2ID);
        $this->userModel->resetPassword($user['id'], $newPassword);
        $userUpdated = $this->userModel->findByEmail($email);
        $this->assertTrue(password_verify('NewPass123!', $userUpdated['password']));
    }

    public function testValidationToken()
    {
        $email = 'testuser@example.com';
        $user = $this->userModel->findByEmail($email);
        $token = bin2hex(random_bytes(32));
        $this->userModel->updateValidationToken($user['id'], $token);
        $userByToken = $this->userModel->findByValidationToken($token);
        $this->assertTrue(!empty($userByToken));
        $this->assertSame($user['id'], $userByToken['id']);
    }

    public function testPhpUnitSanityCheck()
    {
        echo "Parent class: " . get_parent_class(
            $this
        ) . "\n";
        if (get_parent_class($this) !== 'PHPUnit\\Framework\\TestCase') {
            throw new Exception('PHPUnit TestCase not loaded: ' . get_parent_class($this));
        }
    }
}
