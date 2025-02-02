<?php

class RoomPostModel
{
    private $db;

    public function __construct()
    {
        // Initialize the Database connection
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Retrieve all room posts

    // Retrieve all room posts with user details including age
    public function getAllRooms()
    {
        $sql = "SELECT r.id, r.user_id, r.title, r.description, r.rent, r.condition, r.room, r.address, r.photo, 
                       u.username, u.phone, u.email, u.gender, u.created_at, 
                       ud.fullname, ud.dob, ud.NID, ud.profile_photo, ud.userType, 
                       TIMESTAMPDIFF(YEAR, ud.dob, CURDATE()) AS age 
                FROM room_post r
                JOIN users u ON r.user_id = u.id
                JOIN user_details ud ON r.user_id = ud.user_id";
        
        $result = $this->db->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC); // Return data as an associative array
        }

        return []; // Return an empty array if no records are found
    }


// Retrieve all room posts by a specific user
public function getRoomsByUserId($user_id)
{
    $sql = "SELECT r.id, r.user_id, r.title, r.description, r.rent, r.condition, r.room, r.address, r.photo, 
                       u.username, u.phone, u.email, u.gender, u.created_at, 
                       ud.fullname, ud.dob, ud.NID, ud.profile_photo, ud.userType, 
                       TIMESTAMPDIFF(YEAR, ud.dob, CURDATE()) AS age 
                FROM room_post r
                JOIN users u ON r.user_id = u.id
                JOIN user_details ud ON r.user_id = ud.user_id 
                WHERE r.user_id = ?"; // Specify r.user_id here to avoid ambiguity
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $user_id); // Bind the user_id parameter
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any rows are returned
    if ($result->num_rows > 0) {
        $rooms = [];
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row; // Add each row to the rooms array
        }
        return $rooms; // Return an array of rooms
    }

    return null; // Return null if no rooms are found
}


    // Retrieve a single room post by ID
    public function getRoomById($id)
    {
        $sql = "SELECT `id`, `user_id`, `title`, `description`, `rent`, `condition`, `room`, `address`, `photo` FROM `room_post` WHERE `id` = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc(); // Return a single record as an associative array
        }

        return null; // Return null if no record is found
    }

    // Add a new room post
    public function addRoom($user_id, $title, $description, $rent, $condition, $room, $address, $photo)
    {
        $sql = "INSERT INTO `room_post` (`user_id`, `title`, `description`, `rent`, `condition`, `room`, `address`, `photo`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("issssiss", $user_id, $title, $description, $rent, $condition, $room, $address, $photo);

        return $stmt->execute(); // Returns true if the query was successful, false otherwise
    }

    // Update an existing room post
    public function updateRoom($id, $title, $description, $rent, $condition, $room, $address, $photo)
    {
        $sql = "UPDATE `room_post` 
                SET `title` = ?, `description` = ?, `rent` = ?, `condition` = ?, `room` = ?, `address` = ?, `photo` = ? 
                WHERE `id` = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssssisi", $title, $description, $rent, $condition, $room, $address, $photo, $id);

        return $stmt->execute(); // Returns true if the query was successful, false otherwise
    }

    // Delete a room post
    public function deleteRoom($id)
    {
        $sql = "DELETE FROM `room_post` WHERE `id` = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute(); // Returns true if the query was successful, false otherwise
    }
}
?>
