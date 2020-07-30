<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Spiza</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script src="<?php echo __SITE_URL; ?>/view/javascript/user.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <link rel="icon" href="<?php echo __SITE_URL; ?>/css/logo.png" />
    <link rel="stylesheet" href="<?php echo __SITE_URL; ?>/css/preIgnore.css">

</head>

<body>

<div class="jumbotron text-center" style="margin-bottom: 0px;">
    <h1>Spiza.hr</h1>
</div>

<nav class="navbar navbar-expand-sm sticky-top bg-dark navbar-dark" style="margin-bottom: 50px; z-index: 2;">
    <a class="navbar-brand" href="#">
        <img src="<?php echo __SITE_URL; ?>/css/logo.png" alt="Spiza.hr" style="width:40px;filter: brightness(0) invert(1);">
    </a>
    <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="<?php echo __SITE_URL; ?>/index.php?rt=index/logout">Logout</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo __SITE_URL; ?>/index.php?rt=user/orders">Moje narudžbe</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo __SITE_URL; ?>/index.php?rt=user">Vaši omiljeni restorani</a></li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                Restorani
            </a>
                <div class="dropdown-menu bg-dark">
                    <a style="color: grey" class="dropdown-item" href="<?php echo __SITE_URL; ?>/index.php?rt=user/popular">Popularni restorani</a>
                    <a style="color: grey" class="dropdown-item" href="<?php echo __SITE_URL; ?>/index.php?rt=user/restaurants">Svi restorani</a>
                    <a style="color: grey" class="dropdown-item" href="<?php echo __SITE_URL; ?>/index.php?rt=user/neighborhood">U kvartu</a>
                    <a style="color: grey" class="dropdown-item" href="<?php echo __SITE_URL; ?>/index.php?rt=user/foodType">Prema vrsti hrane</a>
                </div>
            </li>
    </ul>
</nav> 


<div class="container">
<div class="row">
<div class="col-12">

     <?php
    if( isset($errorFlag))
        if( $errorFlag )
            echo $errorMsg . '<br>';
    ?>

<h2 id="naslov"><?php echo $title; ?></h2>
