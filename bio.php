<?php
require 'config.php';

	//set variables for signed in userinfo
	$userID = $_POST['userID'];
	$displayName = $_POST['displayName'];
	$userBio= mysqli_real_escape_string($connect, $_POST['userBio']);
	$userImage= "'uploads/'". $_POST['userImage'];
	$userBioText= $_POST['userBio'];

	//get initial load as true
	$initialLoad= $_POST['initialLoad'];

	//if initial load query database for user data
	if ($initialLoad == 'true') {
		$query= "SELECT displayname, bio, pictureid FROM userinfo WHERE userid='$userID'";
		$run= mysqli_query($connect, $query);
		$num_row = mysqli_num_rows($run);
		$row=mysqli_fetch_assoc($run);

		//retrieve array of user info
		if($num_row == 1 ) {
			///set array variables
			$displayName= $row['displayname'];
			$userBioText= str_replace("'", "*", $row['bio']);
			$userImage = $row['pictureid'];

			//hidden form to pass retrieved values to chat.php
			echo "<form id='UserInfoForm' method='post' action='chat.php' hidden>
					<input id='displayName' value='$displayName'/>
  					<input id='userBioText' value='$userBioText'/>
  					<input id='userPicture' value='$userImage'/>
					</form>";


		}
		//if no data
		else {
			echo 'false';
		}
	}
	else {
			//update database with new values
			echo $displayName;
			$query= "UPDATE userinfo SET displayname = '$displayName', bio = '$userBio' WHERE userid = $userID";

			$run= mysqli_query($connect, $query);
	}
	
?>