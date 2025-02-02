<?php

class Food extends Controller {
    public function index() {

        // Check if the user is logged in by verifying the session
        if (!isset($_SESSION['user_id'])) {
            // Redirect to the login page if the user is not logged in
            header("Location: login");
            exit();
        }

        // If the user is logged in, load the food view
        $this->view('food');
    }
}

// Instantiate the food controller and call the index method
$food = new Food;
$food->index();