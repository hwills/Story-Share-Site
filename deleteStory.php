<?php
session_start();
$username = $_SESSION['username'];

//begin mysql session
$mysqli = new mysqli('localhost', 'webuser', 'webpass', 'newspage');

if($mysqli->connect_errno){
	print("CONNECTION ERROR YOU FAILURE!");
	exit;
} 

//get story to delete
$storyid=$_GET['storyid'];

//delete story comments
$stmt2 = $mysqli->query("DELETE from comments WHERE story_id=".$storyid);
if(!$stmt2){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

//delete story
$stmt = $mysqli->query("DELETE from stories WHERE story_id=".$storyid);
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

//return to viewing stories
header('Location: ./mainPage.php');
die();

?>
