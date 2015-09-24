<!DOCTYPE html>
<html>
<head>
        <title> Stories!!! :) </title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap -->
    	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    	<link href="mainPage.css" rel="stylesheet" media="screen">
</head>
<body>
<?php
session_start();
if(isset($_SESSION['username'])){//if the user is login give them options to create a story or logout
	$username = $_SESSION['username'];

	echo "
		<div class=\"container\">

      <div class=\"masthead\">
        <h3 class=\"muted\">". $username ."'s Profile</h3>
        <div class=\"navbar\">
          <div class=\"navbar-inner\">
            <div class=\"container\">
              <ul class=\"nav\">
                <li><a href=\"mainPage.php\">Front</a></li>
                <li class=\"active\"><a href=\"sports.php\">Sports</a></li>
                <li><a href=\"morbid.php\">Morbid</a></li>
                <li><a href=\"music.php\">Music</a></li>
                <li><a href=\"funny.php\">Funny</a></li>
                <li><a href=\"news.php\">News</a></li>
                <li><a href=\"logout.php\">Logout</a></li>
              </ul>
            </div>
          </div>
        </div><!-- /.navbar -->
      </div>



	";

    echo "<p><a class = \"btn\" href = \"createStory.php\"> Add Story</a></p>";
    
}
//<!-- Sign In/Sign Up Button -->
else if(!isset($_SESSION['attempts'])){
	echo "
		<div class=\"container\">

      <div class=\"masthead\">
        <h3 class=\"muted\"></h3>
        <div class=\"navbar\">
          <div class=\"navbar-inner\">
            <div class=\"container\">
              <ul class=\"nav\">
                <li><a href=\"mainPage.php\">Front</a></li>
                <li class=\"active\"><a href=\"sports.php\">Sports</a></li>
                <li><a href=\"morbid.php\">Morbid</a></li>
                <li><a href=\"music.php\">Music</a></li>
                <li><a href=\"funny.php\">Funny</a></li>
                <li><a href=\"news.php\">News</a></li>
                <li><a href=\"login.html\">SIGNUP</a></li>
                <li><a href=\"login.html\">SIGNIN</a></li>
              </ul>
            </div>
          </div>
        </div><!-- /.navbar -->
      </div>



	";
	
}
else{
	echo "
		<div class=\"container\">

      <div class=\"masthead\">
        <h3 class=\"muted\"></h3>
        <div class=\"navbar\">
          <div class=\"navbar-inner\">
            <div class=\"container\">
              <ul class=\"nav\">
                <li><a href=\"mainPage.php\">Front</a></li>
                <li class=\"active\"><a href=\"sports.php\">Sports</a></li>
                <li><a href=\"morbid.php\">Morbid</a></li>
                <li><a href=\"music.php\">Music</a></li>
                <li><a href=\"funny.php\">Funny</a></li>
                <li><a href=\"news.php\">News</a></li>
              </ul>
            </div>
          </div>
        </div><!-- /.navbar -->
      </div>



	";
	if(isset($_SESSION['attempts'])){
		unset($_SESSION['attempts']);
	}
}
//open mysql session
$mysqli = new mysqli('localhost', 'webuser', 'webpass', 'newspage');


if($mysqli->connect_errno){
	print("CONNECTION ERROR YOU FAILURE!");
	exit;
} 

//set the creator to know what they can edit / delete
if(isset($_SESSION['userid'])){
	$crntcreator=$_SESSION['userid'];
}
else{
	$crntcreator=0;
}
$ids;
$stories;
$creators;
$names;
$votes;

//get the stories
$stmt = $mysqli->prepare("select story_id, story_title, creator_id, users.username, votes from stories join users on (users.id=creator_id) where category='Sports' order by votes desc");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
 
$stmt->execute();
 
$stmt->bind_result($ids, $stories, $creators, $names, $votes);
echo "<div class = \"container-fluid\">"; 
 
//display the stories
echo "<ul>\n";
while($stmt->fetch()){
	echo "<div class = \"row\" >";
	echo "<form class = \"form-horizontal\" action=\"storyPage.php\" method=\"GET\">";
	echo "<div class = \"form-group\" >";
	printf("<label class = \"col-sm-2 control-label\">[VOTES: %s]</label><input type=\"hidden\" name=\"storyid\" value=\"%s\">
		<div class = \"col-sm-10\">
		<p class = \"form-control-static\">%s</p>
		<p style = \"text-align: right; \">--by %s</p>
		
		<input type = \"submit\" value=\"Open Story\">
		
		</div>",
	htmlspecialchars($votes),
	htmlspecialchars($ids), 
	htmlspecialchars($stories),
	htmlspecialchars($names));
	echo "</div>";
	echo "<div class = \"form-group\" >";
	echo "<input type=\"submit\" value=\"Upvote\" formaction=\"upvote.php\">";
	echo "<input type=\"submit\" value=\"Downvote\" formaction=\"downvote.php\">";
	
	if(htmlspecialchars($creators)==$crntcreator){

		printf("\t<input type=\"hidden\" name=\"storyid\" value=\"%s\"><input type = \"submit\" value=\"Delete\" formaction=\"deleteStory.php\">\n",
		htmlspecialchars($ids));
		printf("\t<input type=\"hidden\" name=\"storyid\" value=\"%s\"><input type = \"submit\" value=\"Edit\" formaction=\"editStory.php\">\n",
		htmlspecialchars($ids));
	}
	echo "</div>";
	echo "</form>";
	
	echo "</div>";
}
echo "</ul>\n";
echo "</div>";
$stmt->close();

//
//
//Sign In button / create account button
//
//Submit new text story;
//
//Submit link to story
//
//for(story #x to x+10){
//	Print href to storypage
//}
//
//
?>
	<script src="http://code.jquery.com/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>

</body>
</html>
