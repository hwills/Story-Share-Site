<?php
session_start();
$username = $_SESSION['username'];

//beign mysql session
$mysqli = new mysqli('localhost', 'webuser', 'webpass', 'newspage');

if($mysqli->connect_errno){
	print("CONNECTION ERROR YOU FAILURE!");
	exit;
} 

//get the comment that we are adding
if(isset($_GET['comment'])){
	$comment=$_GET['comment'];
}
$id=$_GET['storyid'];

//get the user adding the comment
$creator_id = $_SESSION['userid'];

$stmt = $mysqli->prepare("insert into comments (comment_content, story_id, creator_id, creator_name) values (?, ?, ?, ?)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('siis', $comment, $id, $creator_id, $username);
 
$stmt->execute();
 
$stmt->close();

//return them to the proper storypage
$_SESSION['storyid'] = $id;

header('Location: ./storyPage.php');
die();

?>
