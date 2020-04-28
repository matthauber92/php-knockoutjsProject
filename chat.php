<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Log10 Chat</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.9/css/all.css" integrity="sha384-5SOiIsAziJl6AWe0HWRKTXlfcSHKmYV4RBF18PPJ173Kzn7jzMyFuTtk8JA7QQG1" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href='css/stylesheet.css' rel='stylesheet'>
  <script type="text/javascript" src="js/log.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.4.2/knockout-debug.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.4.2/knockout-min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.4.2/knockout-debug.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.4.2/knockout-min.js"></script>
  <?php
  session_start();
  include('config.php');
  //check for session variables in console
  if(isset($_SESSION['uname'])) {
    echo "<script>
    console.log(" . "'" . $_SESSION['uname'] ."'" . ");
  </script>";
  echo "<script>
  console.log(" . "'" . $_SESSION['id'] ."'" . ");
</script>";
echo "<script>
console.log(" . "'" . $_SESSION['userSearched'] ."'" . ");
</script>";
}
else{
  header('location:index.php');
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
  <!--Hidden form to set Session values-->
  <form method="GET" hidden>
    <input id="user" value="<?php echo $_SESSION['uname']?>"/>
    <input id="userId" value="<?php echo $_SESSION['id']?>"/>
    <input id="tempId" value="<?php echo $_SESSION['tempUserId']?>"/>
  </form>
  <script type="text/javascript">
    //retrieve session values for ko display
    var user= document.getElementById("user").value;
    var userId= document.getElementById("userId").value;
    var tempId= document.getElementById("tempId").value;
    //boolean for user homepage
    var profileVisited = false;

    //ko UserViewModel object to store data in object properties
    UserViewModel = {
      ID: ko.observable(),
      TempID: ko.observable(),
      UserName: ko.observable(""),
      DisplayName: ko.observable(""),
      TempDisplayName: ko.observable(""),
      Date: ko.observable(new Date()),
      Bio: ko.observable(""),
      TempBio: ko.observable(""),
      EditProfile: ko.observable(false),
      IsEditable: ko.observable(false),
      ImageSrc: ko.observable(""),
      TempImageSrc: ko.observable(""),
      ImageObject: ko.observableArray([]),
      FileUpload: ko.observable(false),
      NewCommentText: ko.observable(""),
      NewPostText: ko.observable(""),
      Posts: ko.observableArray([]),
      Comments: ko.observableArray([]),


      //function to check boolean for user profile edit
      ProfileEdited: function() {
        if (UserViewModel.IsEditable()) {
          if (UserViewModel.EditProfile()) {
            UserViewModel.EditProfile(false);
          }
          else {
            UserViewModel.EditProfile(true);
          }
        }
      },

      //store initial user page data
      InitialLoad: function() {
        //store logged in username && ID
        UserViewModel.UserName(user);
        UserViewModel.ID(userId);
        //tempId created at login to verify user profile views
        UserViewModel.TempID(tempId);
        //set inition ID to user logged in ID
        var initialLoadID = UserViewModel.ID();
        //verify IDs are set to each other
        if(UserViewModel.ID() == UserViewModel.TempID()){
          //if set user can edit bio
          UserViewModel.IsEditable(true);
          $("#EditProfileDiv").addClass('profileEditable');
        }
        else {
          //if ID's return false no edit
          initialLoadID = UserViewModel.TempID();
          UserViewModel.IsEditable(false);
          $("#EditProfileDiv").removeClass('profileEditable');
        }
        //return user ID after profile visit
        if (profileVisited) {
          initialLoadID = UserViewModel.TempID();
        }

        /** AJAX CALL TO 'bio.php' TO RETRIEVE PROFILE FROM 'USER INFO' TABLE **/
        $.ajax({
          url: 'bio.php',
          type: 'POST',
          data: {
            userID: initialLoadID,
            displayName:"",
            userBio:"",
            userImage:"",
            initialLoad: true,
              //fileUpload: UserViewModel.FileUpload()
              
            },
            complete: function(data) {

              // if ($("#UserInfoForm").length > 0) {
              //   $("#UserInfoForm").remove();
              //}

              //append user data to page
              $("body").append(data.responseText);
              //retrieve POST variable from 'bio.php'
              var displayname = document.getElementById("displayName").value;
              var bio = document.getElementById("userBioText").value.replace("*", "'");
              var img = document.getElementById("userPicture").value;

              //store data in model
              UserViewModel.DisplayName(displayname);
              UserViewModel.Bio(bio);
              UserViewModel.ImageSrc(img);

              UserViewModel.TempDisplayName(displayname);
              UserViewModel.TempBio(bio);
              UserViewModel.TempImageSrc(img);

              //function to populate page with user posts
              UserViewModel.RetrievePosts();

              //on user homepage
              profileVisited = false;
            },
            //if function executed correctly
            success: function(data) {
              console.log("success");
            },
            //function failed
            error: function(e) {
              console.log("Fail");
            },

          });
      },

      //set function and parameter to read file pathway
      UploadPicture: function(file) {
        //set model data to retrieve pathway and file name
        UserViewModel.ImageObject(file);
        var fileName = file.name;
        //pic file path of visitied user
        UserViewModel.TempImageSrc(fileName);
        UserViewModel.ImageSrc(fileName);
        //set new picture
        UserViewModel.FileUpload(true);
      },

      //save appened user data function
      SaveProfile: function() {

        //jQuery trigger to click hidden save button
        $("#SubmitProfilePicture").click();

          //get display values from user input before save
          UserViewModel.DisplayName(UserViewModel.TempDisplayName());
          UserViewModel.Bio(UserViewModel.TempBio());

          //check for file path
          var fileInput = document.getElementById("fileToUpload").files;
          if (fileInput.length > 0) {
            //insert into 'uploads folder on server'
            UserViewModel.ImageSrc("uploads/" + fileInput[0].name);
          }

          /** AJAX CALL TO PHP TO UPDATE PROFILE. UPDATES 'USER INFO' TABLE **/
          $.ajax({
            url: 'bio.php',
            type: 'POST',
            data: {
              userID: UserViewModel.ID(),
              displayName:UserViewModel.DisplayName(),
              userBio:UserViewModel.Bio(),
              userImage:UserViewModel.ImageSrc(),
              initialLoad: false

            },
            //check for successful update
            success: function(e) {
              console.log("success");
            },
            error: function(e) {
              console.log("Fail");
            },

          });

          //new data set prevent default from setting
          UserViewModel.EditProfile(false);


        },

        /** AJAX CALL TO PHP TO SEARCH PROFILE. QUERY 'USERINFO' TABLE **/
        Search: function() {
          $.ajax({
            url: 'search.php',
            type: 'POST',
            data: {
              userID: UserViewModel.ID(),
              search:UserViewModel.DisplayName()
            },
            success: function(e) {
              console.log("success");
            },
            error: function(e) {
              console.log("Fail");
            },

          });


        },

        //function for user to submit posts
        AddNewPost: function() {
          //check user input data-bind from html
          if (UserViewModel.NewPostText().length > 0) {
            //add new posts in array and return array length
            UserViewModel.Posts.unshift({
              date: new Date(),
              content: UserViewModel.NewPostText(),
              comments: []
            });

            /** AJAX CALL TO PHP TO SET NEW POSTS. QUERY 'POSTS' TABLE **/
            $.ajax({
              url: 'addPosts.php',
              type: 'POST',
              data: {
                userID: UserViewModel.ID(),
                posterdisplay: UserViewModel.DisplayName(),
                post_detail:UserViewModel.NewPostText(),
                like_count:0
              },
              complete: function(data){
                UserViewModel.RetrievePosts();
              },
              success: function(e) {
                console.log("success");
                console.log(e);
              },
              error: function(e) {
                console.log("Fail");
                console.log(e);
              },

            });

            //set post input to default
            UserViewModel.NewPostText("");

            /** AJAX CALL TO PHP TO ADD THE POST TO THE 'Posts' TABLE **/

            
          }
        },

        //function to display posts
        RetrievePosts: function() {

          /** AJAX CALL TO PHP TO RETRIEVE POSTS FROM THE 'Posts' TABLE **/
          $.ajax({
            url: 'retrievePosts.php',
            type: 'POST',
            data: {
              userID: UserViewModel.TempID() //pass in UserViewModel.TempID()
            },
            complete: function(data) {
              //parse JSON array
              var tempPosts = JSON.parse(data.responseText);
              //store posts and comments in separate arrays
              var posts = [];
              var comments = [];

              //ko observable array to store data in model object
              UserViewModel.Posts([]);

              //interate through JSON array
              for (var i = 0; i < tempPosts.length; i++) {
                //create object to store JSON comment data and populate comments array
                comments.push(
                {
                  commenterid: tempPosts[i].commenterid,
                  commenterdisplay: tempPosts[i].commenterdisplay,
                  comment_detail: tempPosts[i].comment_detail,
                  childpostid: tempPosts[i].childpostid
                }
                );
                //create object to store JSON posts data and populate posts array
                posts.push(
                {
                  postid: tempPosts[i].postid,
                  posterid: tempPosts[i].posterid,
                  posterdisplay: tempPosts[i].posterdisplay,
                  post_detail: tempPosts[i].post_detail,
                  like_count: tempPosts[i].like_count,
                  comments: []
                }
                );
              }

              //iterate through posts array
              for (var i = 0; i < posts.length; i++) {
                //iterate and get comments from posts
                for (var k = 0; k < comments.length; k++) {
                  if(posts[i].postid == comments[k].childpostid) {
                    posts[i].comments.push(comments[k]);
                  }
                }
              }

              var initialPost = 0;

              //iterate and store postid data in UserViewModel
              for (var i = 0; i < posts.length; i++) {
                if (posts[i].postid != initialPost) {
                  UserViewModel.Posts.push(posts[i]);
                  initialPost = posts[i].postid;
                }
              }
              //UserViewModel.Posts(posts);

            },
            success: function(e) {
              console.log("success");
            },
            error: function(e) {
              console.log("Fail");
            },

          });

        },
    
    //function RetrievePublicPosts for all profile views
    RetrievePublicPosts: function() {
        /** AJAX CALL TO PHP TO RETRIEVE POSTS FROM THE 'Posts' TABLE **/
          $.ajax({
            url: 'retrievePosts.php',
            type: 'POST',
            data: {
              userID: 0 //ALWAYS pass in userID = 0
            },
            /** COMPLETE FUNCTION REPEATS RETRIEVE POSTS FUNCTION **/
            complete: function(data) {
              var tempPosts = JSON.parse(data.responseText);
              var posts = [];
              var comments = [];

              console.log(data);

              UserViewModel.Posts([]);


              for (var i = 0; i < tempPosts.length; i++) {
                comments.push(
                {
                  commenterid: tempPosts[i].commenterid,
                  commenterdisplay: tempPosts[i].commenterdisplay,
                  comment_detail: tempPosts[i].comment_detail,
                  childpostid: tempPosts[i].childpostid
                }
                );
                posts.push(
                {
                  postid: tempPosts[i].postid,
                  posterid: tempPosts[i].posterid,
                  posterdisplay: tempPosts[i].posterdisplay,
                  post_detail: tempPosts[i].post_detail,
                  like_count: tempPosts[i].like_count,
                  comments: []
                }
                );
              }

              for (var i = 0; i < posts.length; i++) {

                for (var k = 0; k < comments.length; k++) {
                  if(posts[i].postid == comments[k].childpostid) {
                    posts[i].comments.push(comments[k]);
                  }
                }
              }

              var initialPost = 0;

              for (var i = 0; i < posts.length; i++) {
                if (posts[i].postid != initialPost) {
                  UserViewModel.Posts.push(posts[i]);
                  initialPost = posts[i].postid;
                }
              }
              //UserViewModel.Posts(posts);
            },
            success: function(e) {
              console.log("success");
            },
            error: function(e) {
              console.log("Fail");
            },
          });
        },

        /**FUNCTION FOR HUB BUTTON TO TOGGLE PUBLIC POSTS VIEW **/
        TogglePublicPosts: function() {
          UserViewModel.RetrievePublicPosts();  
          //users cannot edit in public view
          UserViewModel.IsEditable(false);
        },
    
    

        AddNewComment: function(data, postid) {
      /** Added 'data' and 'postid' parameters to function in order to get pass in 'postid' to ajax **/
          if (UserViewModel.NewCommentText().length > 0) {
            UserViewModel.Comments.unshift({
              date: new Date(),
              content: UserViewModel.NewCommentText(),
            });
            /** AJAX CALL TO PHP TO ADD COMMENTS TO 'comments' TABLE **/
            $.ajax({
              url: 'addComments.php',
              type: 'POST',
              data: {
                userID: UserViewModel.ID(),
                postID: parseInt(postid.postid), //pass in the 'postid' variable passed to the function and convert from array to enable query
                //data-bind user input to get new comment
                comment_detail: UserViewModel.NewCommentText()
              },
              complete: function() {
                
                //after post is added to the 'comments' table call function to retrieve the latest posts
                if (UserViewModel.IsEditable()) {
                    UserViewModel.RetrievePosts();
                }
                else if (UserViewModel.ID() != UserViewModel.TempID()) {
                    UserViewModel.RetrievePosts();
                }
                else if ((UserViewModel.ID() == UserViewModel.TempID()) && UserViewModel.IsEditable() == false) {
                  UserViewModel.RetrievePublicPosts();
                }
                
              },
              success: function(e) {
                console.log("success");
                console.log(e);
              },
              error: function(e) {
                console.log("Fail");
                console.log(e);
              },

            });

            //set default input
            UserViewModel.NewCommentText("");

            
          }
        },

      //function to relog posts on user profile
      RelogPost: function(data, postid) {
            console.log(postid);

            /** AJAX CALL TO PHP TO ADD POSTS 'posts' TABLE **/
            $.ajax({
              url: 'addPosts.php',
              type: 'POST',
              data: {
                userID: UserViewModel.ID(),
                posterdisplay: postid.posterdisplay,
                post_detail: postid.post_detail,
                like_count:0
              },
              complete: function(data){

                //relog post from visted user profile
                if (UserViewModel.ID() != UserViewModel.TempID()) {
                    UserViewModel.RetrievePosts();
                }
                else {
                  //relog user public posts
                  UserViewModel.RetrievePublicPosts();
                }


              },
              success: function(e) {
                console.log("success");
                console.log(e);
              },
              error: function(e) {
                console.log("Fail");
                console.log(e);
              },

            });


      },

      //populate visited user profile with their posts
      ViewProfile: function(data, postid) {
        tempId = postid.posterid
        profileVisited = true;
        UserViewModel.InitialLoad();

      }

    }

    </script>
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
          <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
              <li class="dropdown">
                <ul class="dropdown-menu">
                  <li>
                    <div class="search">
                      <!--Search bar post to search.php-->
                      <form name="search" method="post" action="search.php" onSubmit=" return validate()" style="display:inline-flex;">
                      <!--search DisplayName-->
                        <input type="text" size="40" mexlength="50" id="search" name="search" data-bind="value: DisplayName" required/>
                        <input type="submit" name="Submit" value="Search"/>
                      </form>
                    </div>
                  </li>
                  <!--onClick call function to TogglePublicPosts and populate page-->
                  <li class="navbar-item navList" id="GoToHub"><a class="fakeAnchorTag" onclick="goToHub()" style="cursor: pointer;">Hub</a></li>
                  <!--onClick return original id of logged in user-->
                  <li class="navbar-item navList"><a href="chat.php" onclick="<?php if(isset($_SESSION['tempUserId'])){$_SESSION['tempUserId'] = $_SESSION['id'];}?>">My Profile</a></li>
                  <li class="navbar-item navList"><a href="logout.php" style="font-size:6em; color:red;"><i class="fas fa-sign-out-alt" aria-hidden="true"></i>Logout</a></li>
                </ul>
              </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li>
                <div class="search">
                <!--onClick call function to TogglePublicPosts and populate page-->
                  <form name="search" method="post" action="search.php" onSubmit=" return validate()" style="display:inline-flex;">
                    <!--search DisplayName-->
                    <input type="text" size="40" mexlength="50" id="search" name="search" id="InpStyle" class="inp-style" data-bind="value: DisplayName" required/>
                    <input type="submit" name="Submit" value="Search"/>
                  </form>
                </div>
              </li>
              <li class="navbar-item navList" id="GoToHub"><a class="fakeAnchorTag" onclick="goToHub()" style="cursor: pointer;">Hub</a></li>
              <!--onClick return original id of logged in user-->
              <li class="navbar-item navList"><a href="chat.php" onclick="<?php if(isset($_SESSION['tempUserId'])){$_SESSION['tempUserId'] = $_SESSION['id'];}?>">My Profile</a></li>
              <li class="navbar-item navList"><a href="logout.php" style="font-size:1em; color:red;"><i class="fas fa-sign-out-alt" aria-hidden="true"></i>Logout</a></li>
            </ul>
          </div>
        </nav>
      </div>
    </header>
    <section>
      <div id="UserProfile" class="container">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12">
            <div id="UserInfoContainer">
              <form method="post" action="upload.php" enctype="multipart/form-data">
                <!--data-bind to EditProfile() boolean if true displat button to upload img-->
                <div class="SavePro" data-bind="visible: EditProfile"><input id="fileToUpload" name="fileToUpload" type="file" style="display:none;"/><input name="userID" data-bind="value: ID" hidden/></div>
                <div class="SavePro" data-bind="visible: EditProfile"><input id="SubmitProfilePicture" type="submit" name="submit" value="Submit" style="background-color:orange; display:none;" hidden></div>
              </form>
              <form method="post" action="bio.php" enctype="multipart/form-data">
              <!--click for EditProfile function-->
                <div id="ImageContainer" title="Edit Profile" data-bind="click: ProfileEdited.bind()">
                  <!--Get user img from UserViewModel object property-->
                  <img data-bind="attr: { src: ImageSrc }" width="300px" height="300px" style="float:left;"/>
                  <div id="EditProfileDiv"> 
                    <span style="top: -150px; color:white; position:relative;">EDIT PROFILE </span>
                  </div>
                </div>
                <!--data-bind to edit profile if visible and then display save button-->
                <div class="SavePro" data-bind="visible: EditProfile"><button value="Save Profile" style="background-color:orange;" data-bind="click: SaveProfile.bind()">Save Profile</button></div>
                <div class="SavePro" data-bind="visible: EditProfile">
                  <button id="ProfilePictureSave" style="background-color:orange;">Change Profile Picture</button>
                </div>
                <div id="UserInfo" style="text-align:center;">
                <!--visible when not edit profile-->
                  <span data-bind="text: DisplayName, visible: !EditProfile()" style="text-align: center;"></span>
                  <span data-bind="visible: EditProfile" style="text-align:center; width: 100%;">
                    <input data-bind="value: TempDisplayName" maxlength="50"  style="width: 100%;"/>
                  </span>
                  <span data-bind="text: Bio, visible: !EditProfile()" style="width: 100%; float:left;"></span>
                  <span data-bind="visible: EditProfile" style="width: 100%; float:left;"> 
                    <input type="textarea" data-bind="value: TempBio" maxlength="255" width="50" style="width: 100%; height:200px; float:left; overflow-y: auto;"/> 
                  </span>
                </div>
              </form>
            </div>

            <form method="post" action="addPosts.php" enctype="multipart/form-data">
              <div id="CommentSection">
              <!--Visible when user ID is logged in user to add post-->
                <div id="NewPost" data-bind="visible: IsEditable">
                  <input type="textarea" maxlength="255" data-bind="value: NewPostText"/>
                  <!--onClick call AddNewPost function-->
                  <a><button style="float:left; background-color:#fbb113; margin-right: 30px; margin-top: 20px;" class="btn" data-bind="click: AddNewPost.bind()"> + Add Post </button></a>
                </div>
                <!--loop through posts to display on page-->
                <ul data-bind="foreach: Posts" style="list-style:none; float:left;"> 
                  <li>
                    <span data-bind="text: 'Created by: ' + $data.posterdisplay, click: $root.ViewProfile.bind($data, $data.postid)" style="width: 100%; float:left; font-weight: bold; font-size: 14px; color: blue; cursor:pointer;"></span>
                    <!--<span style="font-size: 8px; width: 100%; float:left;" data-bind="text: $data.date"></span>-->
                    <br />
                    <span data-bind="text: $data.post_detail" style="width: 100%; float:left; min-height: 50px; text-align:center; border-bottom:1px solid black; border-top:1px solid black; padding:10px;font-weight:bold;"></span>
                    <span style="width: 100%; float:left;"> Comments </span>
                    <!--loop through comments to display-->
                    <ul data-bind="foreach: $data.comments" style="list-style:none; float:left; width: 100%; color:gray;">
                      <li style="padding:10px;">
                        <span data-bind="visible: $data.commenterdisplay != null, text: $data.commenterdisplay + ' says: '"></span>
                        <span data-bind="text: $data.comment_detail"></span>    
                      </li>
                    </ul>
                    <span style="width: 100%; float:left;">
                    <!--data-bind to gt user comment input-->           
                    <input type="textarea" maxlength="255" data-bind="value: $root.NewCommentText"/>
                    <!--call AddNewComment function-->
                    <a><button style="float:left; background-color:#fbb113; margin-right: 30px; margin-top: 20px;" class="btn" data-bind="click: $parent.AddNewComment.bind($data, $data.postid)"> + Comment </button></a>
                    <!--relog visible when not homepage userID else display-->
                    <a data-bind="visible: $data.posterid != $root.ID()"><button style="float:left; background-color:#fbb113; margin-right: 30px; margin-top: 20px;" id="Relog" class="btn" data-bind="click: $parent.RelogPost.bind($data, $data.postid)"> Relog </button></a>
                    </span>
                  </li>
                </ul>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
    <script type="text/javascript">
        //function call to UserViewModel to display all user posts
        function goToHub() {
          UserViewModel.TogglePublicPosts();
        }

      $(document).ready(function() {
        //activate Knockout script
        ko.applyBindings(UserViewModel, document.getElementById("UserProfile"));
        //call function for to load user page
        UserViewModel.InitialLoad();
        //trigger for hidden upload picture btn
        $('#ProfilePictureSave').click(function (e){
          $('#fileToUpload').click();
          //stop default action of btn
          e.preventDefault();
        });

        $('#Relog').click(function (e){
          window.alert('Post has been Relogged');
        });

      });
      //validate user search input
      function validate() {
      if(document.getElementById("#search").value ==""){
        alert("Enter Search Name");
        return false;
      }
    }

    </script>
</body>
</html>