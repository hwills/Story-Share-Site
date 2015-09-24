<!DOCTYPE html>
<html>
<head>
        <title> Edit Your Story! </title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
	<style type="text/css">
	body {
                background-image: url("writing.png");
                background-size: 100%;
                color: orange;
        }
	</style>
</head>
<body>
<?php
session_start();
$username = $_SESSION['username'];

//start mysql session
$mysqli = new mysqli('localhost', 'webuser', 'webpass', 'newspage');

if($mysqli->connect_errno){
        print("CONNECTION ERROR YOU FAILURE!");
        exit;
}

//get story we are editing
$storyid = $_GET['storyid'];

$name;
$link;
$content;

//get the content that is currently in the story so the user can see it to change it
$stmt = $mysqli->prepare("select story_title, story_link, story_content from stories where story_id=".$storyid);

if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
}

$stmt->execute();

$stmt->bind_result($name, $link, $content);

$stmt->fetch();

//give user options to change their story
echo "<form action=\"logout.php\">";
echo         "<input type=\"submit\" name=\"logout\" value=\"Logout\">";
echo "</form>";
echo "<form action=\"mainPage.php\">";
echo         "<input type=\"submit\" name=\"home\" value=\"Back to Stories\">";
echo "</form>";
echo "<form action=\"updateStory.php\" method=\"get\">";
echo "<input type=\"hidden\" name=\"storyid\" value=".$storyid.">";
echo "Story Name: ";
echo "<input type=\"text\" name=\"storyname\"value=\"".htmlspecialchars($name)."\"><br>";
echo "Story Link (Optional): ";
echo "<input type=\"text\" name=\"storylink\" value=\"".htmlspecialchars($link)."\"><br>";
echo "Story Text (Optional): ";
echo "<input type=\"text\" name=\"storycontent\" value=\"".htmlspecialchars($content)."\"><br>";
echo "Choose a category (Optional): <br>";
echo "<input type=\"radio\" name=\"category\" value=\"All\" checked>All<br>";
echo "<input type=\"radio\" name=\"category\" value=\"Sports\">Sports<br>";
echo "<input type=\"radio\" name=\"category\" value=\"Funny\">Funny<br>";
echo "<input type=\"radio\" name=\"category\" value=\"Morbid\">Morbid<br>";
echo "<input type=\"radio\" name=\"category\" value=\"News\">News<br>";
echo "<input type=\"radio\" name=\"category\" value=\"Music\">Music<br>";
echo "<input type=\"submit\" value=\"Update\">";
echo "</form>";
$stmt->close();
?>
</body>
</html>

