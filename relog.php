<?php
require 'config.php';
	//set relog post variables
	$userID = $_POST['userID'];
	$postDetail = $_POST['post_detail'];
	$posterdisplay = $_POST['posterdisplay'];
    $likeCount = $_POST['like_count'];


    //insert user relog post info to 'posts' table
	$query= "INSERT INTO 
			posts(postid, userid, post_detail, like_count)
			VALUES(NULL, $userID, '$postDetail', $likeCount);";

	$run= mysqli_query($connect, $query);

	echo "<script>window.alert('Post has been Relogged');</script>";

?>