<?php
require 'config.php';
	
	//set userID variable 
	$userID = $_POST['userID'];

	//query to retrive user id post and comment data
	$query= "SELECT a.postid, a.userid as posterid, c.displayname as posterdisplay, a.post_detail, a.like_count, 
			b.userid as commenterid, d.displayname as commenterdisplay, b.comment_detail 
			from posts a
			left join comments b on a.postid=b.postid
			inner join userinfo c on a.userid=c.userid
			left join userinfo d on b.userid=d.userid
			where a.userid=$userID;";

	//retrieve public posts query
	if($userID == 0) {
		$query= "SELECT a.postid, a.userid as posterid, c.displayname as posterdisplay, a.post_detail, a.like_count, 
			b.userid as commenterid, d.displayname as commenterdisplay, b.comment_detail 
			from posts a
			left join comments b on a.postid=b.postid
			inner join userinfo c on a.userid=c.userid
			left join userinfo d on b.userid=d.userid;";
	}

	//run query
	$run= mysqli_query($connect, $query);
	$num_row = mysqli_num_rows($run);

	//store array with posts && comments for JSON
	$return = array();

	//loop through and retrieve populated data
	for ($x = 0; $x < $num_row; $x++) {
		$row = mysqli_fetch_assoc($run);
		array_push($return, array(
					"postid" => $row['postid'],
					"posterid" => $row['posterid'],
					"posterdisplay" => $row['posterdisplay'],
					"post_detail" => $row['post_detail'],
					"like_count" => $row['like_count'],
					"commenterid" => $row['commenterid'],
					"commenterdisplay" => $row['commenterdisplay'],
					"comment_detail" => $row['comment_detail'],
					"childpostid" => $row['postid']

				)
		);
	}
	//encode to JSON
	echo json_encode($return)


	






?>