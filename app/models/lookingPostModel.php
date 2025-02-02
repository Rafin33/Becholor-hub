<?php

class lookingPostModel
{
    private $db;

    public function __construct()
    {
        // Initialize the Database connection
        $database = new Database();
        $this->db = $database->getConnection();
    }

// Fetch all records from the `looking_for` table with the user details (fullname and age)
public function getAllPostsFromAll()
{
    $sql = "
        SELECT 
            lf.id, 
            lf.user_id, 
            lf.description, 
            lf.budget, 
            lf.location, 
            ud.fullname, 
            TIMESTAMPDIFF(YEAR, ud.dob, CURDATE()) AS age ,
            ud.profile_photo
        FROM 
            looking_for lf
        JOIN 
            user_details ud ON lf.user_id = ud.user_id
        WHERE 1
    ";

    $result = $this->db->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC); // Return an associative array of results
    }

    return []; // Return an empty array if no records found
}
public function getAllPosts($userId)
{
    $sql = "
        SELECT 
            lf.id, 
            lf.user_id, 
            lf.description, 
            lf.budget, 
            lf.location, 
            ud.fullname, 
            TIMESTAMPDIFF(YEAR, ud.dob, CURDATE()) AS age ,
            ud.profile_photo
        FROM 
            looking_for lf
        JOIN 
            user_details ud ON lf.user_id = ud.user_id
        WHERE lf.user_id = ? OR 1
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC); // Return an associative array of results
    }

    return []; // Return an empty array if no records found
}

    // Fetch a single record by ID
    public function getPostById($id)
    {
        $sql = "SELECT `id`, `user_id`, `description`, `budget`, `location` FROM `looking_for` WHERE `id` = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc(); // Return the single record as an associative array
        }

        return null; // Return null if no record found
    }

    // Add a new post
    public function addPost($userId, $description, $budget, $location)
    {
        $sql = "INSERT INTO `looking_for` (`user_id`, `description`, `budget`, `location`) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("isds", $userId, $description, $budget, $location); // `isds` = integer, string, double, string
        return $stmt->execute(); // Return true if successful, false otherwise
    }

    // Update a post by ID
    public function updatePost($id, $description, $budget, $location)
    {
        $sql = "UPDATE `looking_for` SET `description` = ?, `budget` = ?, `location` = ? WHERE `id` = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sdsi", $description, $budget, $location, $id); // `sdsi` = string, double, string, integer
        return $stmt->execute(); // Return true if successful, false otherwise
    }

    // Delete a post by ID
    public function deletePost($id)
    {
        $sql = "DELETE FROM `looking_for` WHERE `id` = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id); // `i` = integer
        return $stmt->execute(); // Return true if successful, false otherwise
    }
}
