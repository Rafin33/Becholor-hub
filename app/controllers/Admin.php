<?php

class Admin extends Controller{
 public function index(){
            // Check if the user is logged in by verifying the session
            if (!isset($_SESSION['user_id'])) {
                // Redirect to the login page if the user is not logged in
                header("Location: login");
                exit();
            }
    
    $this->view('admin');

   
 }
}

$Admin = new Admin;
$Admin->index();
