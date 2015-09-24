<?php
session_start();
$username = $_SESSION['username'];

//start mysql session
$mysqli = new mysqli('localhost', 'webuser', 'webpass', 'newspage');

if($mysqli->connect_errno){
	print("CONNECTION ERROR YOU FAILURE!");
	exit;
} 

//get story and comment information to know which comment to delete and which story to return to when doen
$storyid=$_GET['storyid'];
$commentid=$_GET['commentid'];

//delete comment (using query is okay since we are not getting input from user)
$stmt2 = $mysqli->query("DELETE from comments WHERE comment_id=".$commentid);
if(!$stmt2){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

//retun user to proper storypage
$_SESSION['storyid']=$storyid;

header('Location: ./storyPage.php');
die();

?>
