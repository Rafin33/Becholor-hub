<?php


class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Fetch all users
    public function getAllUsers()
    {
        $conn = $this->db->getConnection();
        $query = "SELECT id, username, password, phone, age, gender, created_at FROM users WHERE 1";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $users = [];
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            return $users;
        } else {
            return [];
        }
    }

    // Fetch a single user by ID
    public function getUserById($id)
    {
        $conn = $this->db->getConnection();
        $query = $conn->prepare("SELECT id, username, password, phone, age, gender, created_at FROM users WHERE id = ?");
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }
}
?>