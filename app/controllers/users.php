<?php
require_once '../app/models/UserModel.php';

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // Display all users
    public function showAllUsers()
    {
        $users = $this->userModel->getAllUsers();
        include 'user.php'; // Pass data to the view
    }
}

// Instantiate the controller and display the users
$controller = new UserController();
$controller->showAllUsers();
?>