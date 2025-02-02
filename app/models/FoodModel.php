<?php

class FoodModel
{
    private $db;

    public function __construct()
    {
        // Initialize the Database connection
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Fetch all records from the `food` table
    public function getAllFood()
    {
        $sql = "SELECT `id`, `user_id`, `details`, `photo` FROM `food` WHERE 1";
        $result = $this->db->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC); // Return an associative array of results
        }

        return []; // Return an empty array if no records found
    }

    // Fetch a single food record by ID
    public function getFoodById($id)
    {
        $sql = "SELECT `id`, `user_id`, `details`, `photo` FROM `food` WHERE `id` = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc(); // Return the single record as an associative array
        }

        return null; // Return null if no record found
    }

    // Add a new food record
    public function addFood($userId, $details, $photo)
    {
        $sql = "INSERT INTO `food` (`user_id`, `details`, `photo`) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iss", $userId, $details, $photo); // `iss` = integer, string, string
        return $stmt->execute(); // Return true if successful, false otherwise
    }

    // Update a food record by ID
    public function updateFood($id, $details, $photo)
    {
        $sql = "UPDATE `food` SET `details` = ?, `photo` = ? WHERE `id` = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssi", $details, $photo, $id); // `ssi` = string, string, integer
        return $stmt->execute(); // Return true if successful, false otherwise
    }

    // Delete a food record by ID
    public function deleteFood($id)
    {
        $sql = "DELETE FROM `food` WHERE `id` = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id); // `i` = integer
        return $stmt->execute(); // Return true if successful, false otherwise
    }
}
