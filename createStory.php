<!DOCTYPE html>
<html>
<head>
        <title> Create Your Own Story! </title><!--A fun title for the page-->
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
echo "<form action=\"logout.php\">";//since user has to be signed in to see this page we offer logout option
echo         "<input type=\"submit\" name=\"logout\" value=\"Logout\">";
echo "</form>";
echo "<form action=\"mainPage.php\">";//offer to go back to reading stories
echo         "<input type=\"submit\" name=\"home\" value=\"Back to Stories\">";
echo "</form>";
//get infor from user to create their own story
echo "<form action=\"addStory.php\" method=\"get\">";
echo "Give your story a name: ";
echo "<input type=\"text\" name=\"storyname\"><br>";
echo "Link associated content (Optional): ";
echo "<input type=\"text\" name=\"storylink\"><br>";
echo "Add some lively text to your story (Optional): ";
echo "<input type=\"text\" name=\"storycontent\"><br>";
echo "Choose a category (Optional): <br>";
echo "<input type=\"radio\" name=\"category\" value=\"All\" checked>All<br>";
echo "<input type=\"radio\" name=\"category\" value=\"Sports\">Sports<br>";
echo "<input type=\"radio\" name=\"category\" value=\"Funny\">Funny<br>";
echo "<input type=\"radio\" name=\"category\" value=\"Morbid\">Morbid<br>";
echo "<input type=\"radio\" name=\"category\" value=\"News\">News<br>";
echo "<input type=\"radio\" name=\"category\" value=\"Music\">Music<br>";
echo "<input type=\"submit\" value=\"CREATE!\">";
echo "</form>";
?>
</body>
</html>
