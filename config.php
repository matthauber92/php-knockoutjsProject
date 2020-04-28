<?php
//create server connection
$server = 'sis-teach-01.sis.pitt.edu';
$username   = 'mdh75';
$password   = '3699023';
$database   = 'mdh75';
$connect = mysqli_connect($server, $username,  $password, $database);
$dbSelect = mysqli_select_db($connect, $database);
 
 //check if connect worked
if(!$connect)
{
    exit('Error: could not establish database connection');
}
if(!$dbSelect)
{
    exit('Error: could not select the database');
}
?>