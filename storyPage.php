<!DOCTYPE html>
<html>
<head>
        <title> Read the Story! </title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
	<style type="text/css">
	body {
			background-image: url("book.png");
			background-size: 100%;
			color: #CF5300;
	}
	</style>
</head>
<body>
<?php
session_start();
if(isset($_SESSION['username'])){//if the user is signed in we give them the optinon to sign out
	$username = $_SESSION['username'];
	$userid=$_SESSION['userid'];
	echo "<form action=\"logout.php\">";
	echo         "<input type=\"submit\" name=\"logout\" value=\"Logout\">";
	echo "</form>";
}
//<!-- Sign In/Sign Up Button -->
else{//otherwise they get the option to sign in
	$userid = 0;
	echo "<form action=\"login.html\">";
	echo         "<input type=\"submit\" name=\"signin\" value=\"Sign In / Sign Up\">";
	echo "</form>";
}

//go back to reading other storied button
echo "<form action=\"mainPage.php\">";
echo         "<input type=\"submit\" name=\"home\" value=\"Back to Stories\">";
echo "</form>";


//begin mysql session
$mysqli = new mysqli('localhost', 'webuser', 'webpass', 'newspage');

if($mysqli->connect_errno){
	print("CONNECTION ERROR YOU FAILURE!");
	exit;
} 

//check which story we are opening
if(isset($_GET['storyid'])){
	$crntstry = $_GET['storyid'];
}
else if(isset($_SESSION['storyid'])){
	$crntstry = $_SESSION['storyid'];
	unset($_SESSION['storyid']);
}
else {
	$crntstry = 0;
	echo "NO STORY ERROR.....";
}

$link;
$content;
$id;

//get the content of the story
$stmt1 = $mysqli->prepare("select story_link, story_content, story_id from stories order by story_id");


if(!$stmt1){
	printf("Query Prep1 Failed: %s\n", $mysqli->error);
	exit;
}
 
$stmt1->execute();
 
$stmt1->bind_result($link, $content, $id);

while($stmt1->fetch()){
	if(htmlspecialchars($id)==$crntstry){
		if($link!=null){
			printf("\t<a href=%s>Associated Content</a><br><br>", htmlspecialchars($link));
		}
		if($content!=null){
			echo htmlspecialchars($content);
		}
		break;
	}
}
 
$stmt1->close();

//give option to write a comment if the user is logged in
if(isset($_SESSION['username'])){
	echo "<form action=\"createComment.php\">";
	echo "<input type=\"text\" name=\"comment\">";
	printf("\t<input type=\"hidden\" value=\"%s\" name=\"storyid\">", $crntstry);
	echo "<input type=\"submit\" name=\"mkcmnt\" value=\"Share Your Opinion\">";
	echo "</form>";
}

$editid = 0;
if(isset($_GET['commentid'])){
	$editid = $_GET['commentid'];
}

$creator_id = $userid;
$cmntids;
$comments;
$stryid;
$creators;
$names;
$votes;

//load comments for story
$stmt2 = $mysqli->prepare("select comment_id, comment_content, story_id, creator_id, users.username, votes from comments join users on (users.id=creator_id) order by votes desc");

if(!$stmt2){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
 
$stmt2->execute();
 
$stmt2->bind_result($cmntids,$comments,$stryid,$creators,$names,$votes);
 
echo "<ul>\n";
while($stmt2->fetch()){
	if(htmlspecialchars($stryid)==$crntstry){
		if($editid!=$cmntids){
			printf("\t<li>[VOTES: %s] %s",
			htmlspecialchars($votes),
			htmlspecialchars($comments));
			echo " --by ".htmlspecialchars($names)."</li>";
			echo "<form action=\"upvote.php\" method\"get\">";
			echo "<input type=\"hidden\" name=\"commentid\" value=".htmlspecialchars($cmntids).">";
			echo "<input type=\"hidden\" name=\"storyid\" value=".htmlspecialchars($stryid).">";
			echo "<input type=\"submit\" value=\"Upvote\" formaction=\"upvote.php\">";
        		echo "<input type=\"submit\" value=\"Downvote\" formaction=\"downvote.php\">";
			if(htmlspecialchars($creators)==$creator_id){
				echo "<input type=\"submit\" name=\"delete\" value=\"Delete\" formaction=\"deleteComment.php\">";
				echo "<input type=\"submit\" name=\"edit\" value=\"Edit\" formaction=\"storyPage.php\">";
			}
			echo "</form>";
		}
		else{
			echo "<form action=\"editComment.php\">";
			echo "<input type=\"hidden\" name=\"commentid\" value=".htmlspecialchars($cmntids).">";
			echo "<input type=\"hidden\" name=\"storyid\" value=".htmlspecialchars($stryid).">";
			echo "<input type=\"text\" name=\"comment\" value=\"".htmlspecialchars($comments)."\">";
			echo "<input type=\"submit\" name=\"edit\" value=\"Submit Edit\">";
			echo "</form>";
		}
	}
}
echo "</ul>\n";
 
$stmt2->close();
?>
</body>
</html>
