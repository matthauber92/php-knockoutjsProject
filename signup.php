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
                <div style="margin-top:45px;">
                <h1 class="navHead site-title">Log10 Social</h1>
                <i class="fas fa-signal"></i>
                </div>
            </div>
        </nav>
    </div>
</header>
<?php
//signup.php
include 'config.php';
 
echo '<h3>Sign up</h3>';
 
if($_SERVER['REQUEST_METHOD'] != 'POST') 
{
    //Echo Sign Up form
    echo '<div class="login-block login-box">
    <div class="logo">
        <i class="fas fa-user" style="font-size: 8em; color:#524f4f;"></i>
    </div>
    <form action="" method="post">
       UserName: <input type="text" placeholder="Username" id="username" name="username" class="username"/>
       Password: <input type="password" placeholder="Password" id="password" name="password" class="password"/>
       Repeat Password: <input type="password" name="user_pass_check"/>
        <input type="submit" value="Add User" id="loginbutton" name="loginbutton" class="login"/>"
    </form>
</div>';
}
else {
    //Array to check for error validation
    $errors = array();
     
     //Check for userinput
    if(isset($_POST['username']))
    {
        //Check characters for alphanumeric
        if(!ctype_alnum($_POST['username'])) {
            $errors[] = 'The username can only contain letters and digits.';
        }
        //limit character length
        if(strlen($_POST['username']) > 30) {
            $errors[] = 'The username cannot be longer than 30 characters.';
        }
    }
    else {
        //check for empty input
        $errors[] = 'The username field must not be empty.';
    }
     
     //check pass input
    if(isset($_POST['password'])) {
        //check for password validation
        if($_POST['password'] != $_POST['user_pass_check']) {
            $errors[] = 'The two passwords did not match.';
        }
    }
    else {
        //password input not empty
        $errors[] = 'The password field cannot be empty.';
    }
     
     //check errors thrown in array
    if(!empty($errors)) {
        echo 'Uh-oh.. a couple of fields are not filled in correctly..';
        echo '<ul>';
        //display all errors in array
        foreach($errors as $key => $value) {
            echo '<li>' . $value . '</li>'; 
        }
        echo '</ul>';
    }
    else {
        //all user inputs correct submit to 'users' table
        $user= mysqli_real_escape_string($connect, $_POST['username']);
        //encrypt password
        $pass= md5($_POST['password']);
        $sql = "INSERT INTO
                    users(userid, username, password, date_created)
                VALUES(NULL, '$user', '$pass', NOW())";
        //execute query
        $result = mysqli_query($connect, $sql);
               
        //submit to 'userinfo table'
        $sql = "INSERT INTO
                    userinfo(userid, username, displayname, date_created, bio, pictureid)
                VALUES((SELECT max(userid) from users), '$user', 'New User', NOW(), 'Welcome!', 'uploads/blank_user.png')";
        //execute query                 
        $result = mysqli_query($connect, $sql);

        //check if query executed
        if(!$result) {
            //display the error
            echo 'Something went wrong while registering. Please try again later.';
            echo mysqli_error($connect); //debugging purposes, uncomment when needed
        }
        else {?>
            <script>window.alert("You have Successfully created an Account!");
            window.location.href= "index.php";</script>
            <?php
            echo 'Successfully registered. You can now <a href="index.php">sign in</a> and start posting! :-)';
        }
    }
}
?>
</body>
</html>