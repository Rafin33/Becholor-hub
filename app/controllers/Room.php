
<?php

class Room extends Controller{
 public function index(){
            // Start the session

            // Check if the user is logged in by verifying the session
            if (!isset($_SESSION['user_id'])) {
                // Redirect to the login page if the user is not logged in
                header("Location: login");
                exit();
            }
    
    $this->view('room');
   
 }
}

$Room = new Room;
$Room->index();
