<?php
require 'config.php';
	//set post variables
	$userID = $_POST['userID'];
	$postDetail = $_POST['post_detail'];
	$posterdisplay = $_POST['posterdisplay'];
    $likeCount = $_POST['like_count'];


    //insert user post info to 'posts' table
	$query= "INSERT INTO 
			posts(postid, userid, post_detail, like_count)
			VALUES(NULL, $userID, '$postDetail', $likeCount);";

	$run= mysqli_query($connect, $query);

?>