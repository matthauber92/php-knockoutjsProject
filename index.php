<!DOCTYPE html>
<html>
<title>Log10 Social Sign In</title>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300" rel="stylesheet">
<script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.9/css/all.css" integrity="sha384-5SOiIsAziJl6AWe0HWRKTXlfcSHKmYV4RBF18PPJ173Kzn7jzMyFuTtk8JA7QQG1" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link href='css/stylesheet.css' rel='stylesheet'>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300" rel="stylesheet">
<script type="text/javascript" src="js/log.js"></script>
<?php
include('config.php');
?>
<style>
body {
    background: url('1.jpg') no-repeat fixed center center;
    background-size: cover;
}
</style>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<header>
<div class="container-fluid">
    <nav id="global-nav" class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>                        
                </button>
                <div id="ToggleHeader" class="toggleHead">
                <h1 class="navHead site-title">Log10 Social</h1>
                <i class="fas fa-signal"></i>
                </div>
            </div>
        </nav>
    </div>
</header>
<div class="login-block login-box">
    <div class="logo">
    	<i class="fas fa-user" style="font-size: 8em; color:#524f4f;"></i>
    </div>
    <!--Form for user login input-->
    <form action="check-login.php" method="post">
	    <input type="text" placeholder="Username" id="username" name="username" class="username"/>
	    <input type="password" placeholder="Password" id="password" name="password" class="password"/>
	    <input type="submit" value="Log In" id="loginbutton" name="loginbutton" class="login"/>
        Not a Member?<a href="signup.php">Sign Up!</a>
    </form>
</div>
</body>
</html>
