
<?php

class Profile extends Controller{
 public function index(){
    
    $this->view('profile');
   
 }
}

$Profile = new Profile;
$Profile->index();
