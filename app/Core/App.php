<?php
class App{
    
private function handleURL(){
    $URL = $_GET['url']??'home';
    $URL = explode("/",$URL);
    return $URL;
}
public function loadController(){
    $URL= $this->handleURL();

    $filename="../app/controllers/".ucfirst($URL[0]).".php";

    if(file_exists($filename)){
        require $filename;
    }else{
        $filename="../app/controllers/404.php";
        require $filename;
    }
    
}

}

$app= new App;
$app->loadController();