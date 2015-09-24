<?php
session_start();
$username = $_SESSION['username'];


//initialize mysql connection
$mysqli = new mysqli('localhost', 'webuser', 'webpass', 'newspage');

if($mysqli->connect_errno){
	print("CONNECTION ERROR YOU FAILURE!");
	exit;
} 

//get the associated story name if the user entered it
if(!empty($_GET['storyname'])){
	$storyname=$_GET['storyname'];
}
else{//if they didnt give a story name, we do not allow the story to be made
	header('Location: ./mainPage.php');
	die();
}
//get link to story if there is one
if(!empty($_GET['storylink'])){
	$storylink=$_GET['storylink'];
}
else{
	$storylink=null;
}
//get conetnt of story if there is some
if(!empty($_GET['storycontent'])){
	$storycontent=$_GET['storycontent'];
}
else{
	$storycontent=null;
}

$cat = $_GET['category'];

//identify the person creating this story.
$creator_id = $_SESSION['userid'];

//put information in sql table.
$stmt = $mysqli->prepare("insert into stories (story_title, story_link, story_content, creator_id, creator_name, category) values (?, ?, ?, ?, ?, ?)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->bind_param('sssiss', $storyname, $storylink, $storycontent, $creator_id, $username, $cat);
 
$stmt->execute();
 
$stmt->close();

//return the user to looking at stories
header('Location: ./mainPage.php');
die();

?>
