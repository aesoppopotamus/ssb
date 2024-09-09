<?php

use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    public function testSuccessfulLoginWithMock()
    {
        // Create a mock for the mysqli class
        $mysqliMock = $this->createMock(mysqli::class);

        // Create a mock for the mysqli result class
        $resultMock = $this->createMock(mysqli_result::class);
        $resultMock->method('fetch_assoc')
                   ->willReturn(['username' => 'admin', 'password' => password_hash('admin_password', PASSWORD_DEFAULT)]);

        // Mock the query method to return the result mock
        $mysqliMock->method('query')
                   ->willReturn($resultMock);

        $username = 'admin';
        $password = 'admin_password';

        // Fetch the user and verify the password
        $result = $mysqliMock->query("SELECT * FROM users WHERE username = '$username'");
        $user = $result->fetch_assoc();
        $this->assertTrue(password_verify($password, $user['password']));
    }
}
