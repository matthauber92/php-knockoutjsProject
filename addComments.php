<?php
	require 'config.php';

	//post user && comment variables
	$userID = $_POST['userID'];
	$comment_detail= $_POST['comment_detail'];
	$postID = $_POST['postID'];

	//insert into database
	$query= "INSERT INTO comments(postID, comment_detail, userid)
			 VALUES($postID, '$comment_detail', $userID)";

			 $run= mysqli_query($connect, $query);
?>