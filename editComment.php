<?php
session_start();
$username = $_SESSION['username'];

//open mysql session
$mysqli = new mysqli('localhost', 'webuser', 'webpass', 'newspage');

if($mysqli->connect_errno){
	print("CONNECTION ERROR YOU FAILURE!");
	exit;
} 

//get comment we are editing and story it is coming from
$storyid=$_GET['storyid'];
$commentid=$_GET['commentid'];
$comment=$_GET['comment'];

//update the comment
$stmt = $mysqli->prepare("update comments set comment_content=? where comment_id=".$commentid);
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('s', $comment);

$stmt->execute();

$stmt->close();

//return to proper story
$_SESSION['storyid'] = $storyid;

header('Location: ./storyPage.php');
die();

?>
