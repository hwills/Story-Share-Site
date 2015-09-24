<?php
session_start();
$username = $_SESSION['username'];

//start mysql connection
$mysqli = new mysqli('localhost', 'webuser', 'webpass', 'newspage');

if($mysqli->connect_errno){
	print("CONNECTION ERROR YOU FAILURE!");
	exit;
} 

//check if we are downvoting a comment or a story
if(isset($_GET['commentid'])){

	$commentid=$_GET['commentid'];
	$storyid=$_GET['storyid'];

	//downvote associated comment
	$stmt2 = $mysqli->query("update comments set votes=votes-1 WHERE comment_id=".$commentid);
	if(!$stmt2){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}

	//return to proper story page
	$_SESSION['storyid'] = $storyid;

	header('Location: ./storyPage.php');
	die();

}
else{

	$storyid=$_GET['storyid'];
	
	//downvote associated story
	$stmt2 = $mysqli->query("update stories set votes=votes-1 WHERE story_id=".$storyid);
	if(!$stmt2){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}

	//return to viewing stories
	header('Location: ./mainPage.php');
	die();

}

?>
