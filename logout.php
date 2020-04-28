<?php   
session_start(); //to ensure currnt same session
session_destroy(); //destroy session
header("location:index.php"); //redirect back to login index.php
exit();
?>