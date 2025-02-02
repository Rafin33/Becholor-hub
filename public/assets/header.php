<?php
// Include the UserModel to fetch user details
require_once '../app/models/UserModel.php';
$userModel = new UserModel();
$user = $userModel->getUserById($_SESSION['user_id']);

?>
<!doctype html>
<html lang="en" class="deeppurple-theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="Maxartkiller">

    <title>Home · Bachelor Hub</title>

    <!-- Material design icons CSS -->
    <link rel="stylesheet" href="vendor/materializeicon/material-icons.css">

    <!-- Roboto fonts CSS -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;display=swap" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap-4.4.1/css/bootstrap.min.css" rel="stylesheet">

    <!-- Swiper CSS -->
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div class="row no-gutters vh-100 loader-screen">
        <div class="col align-self-center text-white text-center">
            <img src="img/logo.png" alt="logo">
            <h1 class="mt-3"><span class="font-weight-light">Bachelor</span>Hub</h1>
            <p class="text-mute text-uppercase small"></p>
            <div class="laoderhorizontal">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="mt-4 mb-3">
            <div class="row">
                <div class="col-auto">
                    <figure class="avatar avatar-60 border-0"><img src="img/user1.png" alt=""></figure>
                </div>
                <div class="col pl-0 align-self-center">
                    <h5 class="mb-1">Ammy Jahnson</h5>
                    <p class="text-mute small">Work, London, UK</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="list-group main-menu">
                    <a href="home" class="list-group-item list-group-item-action active"><i class="material-icons icons-raised">home</i>Home</a>
                                  
                   
                   
                    
                    <a href="login" class="list-group-item list-group-item-action"><i class="material-icons icons-raised bg-danger">power_settings_new</i>Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="wrapper homepage">
        <!-- Header -->
        <div class="header">
            <div class="row no-gutters">
                <div class="col-auto">
                    <button class="btn btn-link text-dark menu-btn"><i class="material-icons">menu</i><span class="new-notification"></span></button>
                </div>
                <div class="col text-center"><img src="img/logo-header.png" alt="" class="header-logo"></div>
                <div class="col-auto">
                    <a href="notification.php" class="btn btn-link text-dark position-relative"><i class="material-icons">notifications_none</i><span class="counts">9+</span></a>
                </div>
            </div>
        </div>
        <div class="container">
<div class="card bg-template shadow mt-4">
    <div class="card-body">
        <div class="row">
            <div class="col-auto">
                <figure class="avatar avatar-60">
                    <img src="<?= isset($user['profile_photo']) ? "".$user['profile_photo'] : 'img/user1.png'; ?>" alt="">
                </figure>
            </div>
            <div class="col pl-0 align-self-center">
                <h5 class="mb-1"><?= isset($user['fullname']) ? htmlspecialchars($user['fullname']) : 'Guest User'; ?></h5>
                <p class="text-mute small"><?= isset($user['userType']) ? htmlspecialchars($user['userType']) : 'Not Specified'; ?></p>
            </div>
        </div>
    </div>
</div>
        </div>