
<?php

function spiltURL(){
        $URL = $_GET['url']??'home';
        $URL = explode("/",$URL);
        return $URL;
    }
function loadController(){
        $URL=spiltURL();
    
        $filename="../app/controllers/".ucfirst($URL[0]).".php";
    
        if(file_exists($filename)){
            require $filename;
        }else{
            $filename="../app/controllers/404.php";
            require $filename;
        }
        
    }
    print_r(spiltURL());
    loadController();
?>