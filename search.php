<?php
include 'config.php';
//start session for search load
session_start();
	
	//retrieve searched username
	$displayName = $_POST['search'];

	//search && filter 'userinfo' table for searched user
	$sql_search="SELECT * FROM userinfo WHERE displayname LIKE '%$displayName%' OR username LIKE '%$displayName%'";
	$query= mysqli_query($connect, $sql_search);

	//if user row found
	if(mysqli_num_rows($query) != 0) {
		//Get array of results
		$searchRes=mysqli_fetch_assoc($query);

		//assign array user variables
		$user= $searchRes['displayname'];
		$id= $searchRes['userid'];

		//change tempUserId session and change display name
		$_SESSION['tempUserId'] = $searchRes['userid'];
		$_SESSION['userSearched'] = $searchRes['displayname'];

		//redirect to searched user profile
		header("Location: chat.php");

	} else {
		//if no user found 'Alert' and redirect
		?>
		<script>window.alert("User Does not Exist");
		window.location.href= "chat.php";</script>
		<?php
	}

?>