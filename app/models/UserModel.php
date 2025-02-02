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
        $query = "SELECT id, username, password, phone, email, gender, created_at FROM users WHERE 1";
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

    public function getUserById($id)
    {
        $conn = $this->db->getConnection();
        $query = $conn->prepare("
            SELECT 
                u.id, u.username, u.password, u.phone, u.email, u.gender, u.created_at, 
                d.fullname, d.dob, d.NID, d.profile_photo, d.userType
            FROM users u
            LEFT JOIN user_details d ON u.id = d.user_id
            WHERE u.id = ?
        ");
        
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();
    
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }
    

    // Register a new user
    public function registerUser($username, $password, $phone, $gender, $email)
    {
        $conn = $this->db->getConnection();

        // Check if the username already exists
        $checkQuery = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $checkQuery->bind_param("s", $username);
        $checkQuery->execute();
        $checkResult = $checkQuery->get_result();

        if ($checkResult->num_rows > 0) {
            return "Username already exists.";
        }

        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert new user
        $query = $conn->prepare("INSERT INTO users (username, password, phone, gender, email, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $query->bind_param("sssss", $username, $hashedPassword, $phone, $gender, $email);

        if ($query->execute()) {
            return "User registered successfully.";
        } else {
            return "Error: " . $query->error;
        }
    }




    public function getUserIdByUsername($username) {
        $conn = $this->db->getConnection();
        $query = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $query->bind_param("s", $username);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['id'];
        }
        return null;
    }




    // Add user details to user_details table
    public function addUserDetails($userId, $fullname, $dob, $nid, $profilePhoto, $userType)
    {
        $conn = $this->db->getConnection();
        $query = $conn->prepare("INSERT INTO user_details (user_id, fullname, dob, NID, profile_photo, userType) VALUES (?, ?, ?, ?, ?, ?)");
        $query->bind_param("isssss", $userId, $fullname, $dob, $nid, $profilePhoto, $userType);

        if ($query->execute()) {
            return "User details added successfully.";
        } else {
            return "Error: " . $query->error;
        }
    }

    // Sign in user
    public function signInUser($username, $password)
    {
        $conn = $this->db->getConnection();

        // Check if the username exists
        $query = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $query->bind_param("s", $username);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                return [
                    "status" => "success",
                    "message" => "Login successful.",
                    "user_id" => $user['id']
                ];
            } else {
                return ["status" => "error", "message" => "Invalid password."];
            }
        } else {
            return ["status" => "error", "message" => "User not found."];
        }
    }

    public function updateUser($userId, $username, $phone, $email, $gender)
    {
        $conn = $this->db->getConnection();
    
        // Update the user's main information
        $query = $conn->prepare("UPDATE users SET username = ?, phone = ?, email = ?, gender = ? WHERE id = ?");
        $query->bind_param("ssssi", $username, $phone, $email, $gender, $userId);
    
        if ($query->execute()) {
            return "User information updated successfully.";
        } else {
            return "Error: " . $query->error;
        }
    }
    
    public function updateUserDetails($userId, $fullname, $dob, $nid, $profilePhoto, $userType)
    {
        $conn = $this->db->getConnection();
    
        // Update the user's additional details
        $query = $conn->prepare("UPDATE user_details SET fullname = ?, dob = ?, NID = ?, profile_photo = ?, userType = ? WHERE user_id = ?");
        $query->bind_param("sssssi", $fullname, $dob, $nid, $profilePhoto, $userType, $userId);
    
        if ($query->execute()) {
            return "User details updated successfully.";
        } else {
            return "Error: " . $query->error;
        }
    }
    


}




?>
