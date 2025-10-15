<?php
class Auth
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Fungsi login
    public function login($username, $password)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Karena password tidak di-hash
            if ($password === $user['password']) {
                return [
                    'status' => true,
                    'role'   => $user['role'],
                    'id'     => $user['id'],
                    'username' => $user['username']
                ];
            }
        }
        return ['status' => false];
    }
}
?>
