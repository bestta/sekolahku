<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../classes/Auth.php';

class AuthTest extends TestCase
{
    private $auth;

    protected function setUp(): void
    {
        global $conn; // dari db.php
        $this->auth = new Auth($conn);
    }

    public function testLoginAdminSuccess()
    {
        // Pastikan di DB kamu ada user admin: username = admin, password = 12345
        $result = $this->auth->login('admin', '12345');
        $this->assertTrue($result['status']);
        $this->assertEquals('admin', $result['role']);
    }

    public function testLoginUserSuccess()
    {
        // Pastikan di DB kamu ada user: username = budi, password = abc123
        $result = $this->auth->login('budi', 'abc123');
        $this->assertTrue($result['status']);
        $this->assertEquals('user', $result['role']);
    }

    public function testLoginFailedWrongPassword()
    {
        $result = $this->auth->login('admin', 'salah');
        $this->assertFalse($result['status']);
    }

    public function testLoginFailedUserNotFound()
    {
        $result = $this->auth->login('tidakada', '12345');
        $this->assertFalse($result['status']);
    }
}
?>
