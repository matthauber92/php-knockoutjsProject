<?php
include("config.php");
//start session for user login
session_start();

//retrieve login username && pass
$uName = $_POST['username'];
$pWord = md5($_POST['password']);

//query database for user login
$qry = "SELECT * FROM users WHERE username='".$uName."' and password='".$pWord."'";
$res = mysqli_query($connect, $qry);
$num_row = mysqli_num_rows($res);
$row=mysqli_fetch_assoc($res);

//if user input found in row start session variables
if( $num_row == 1 ) {
	echo 'true';
	//declare session variables for user
	$_SESSION['uname']= $row['username'];
	$_SESSION['id']= $row['userid'];
	//temp variable when visiting user pages
	$_SESSION['tempUserId']= $row['userid'];
	//redirect to user homepage
	header('location:chat.php');
}
//if row not found
else {
	?>
	<script>window.alert("Invalid UserName or Password");
		window.location.href= "index.php";</script>
	<?php
}
?>