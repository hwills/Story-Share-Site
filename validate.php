<!DOCTYPE html>
<html>
	<head>
	    <title> Validating </title>

	</head>
<body>

	<fieldset style = "border: 0px;">
	<legend><h1> Validating ...</h1> </legend>
	</fieldset>
	<?php
		include 'hashpassword.php';

session_start();
$mysqli = new mysqli('localhost', 'webuser', 'webpass', 'newspage');
		if ($mysqli->connect_errno){
			printf("Connection Failed: %s\n", $mysqli->connect_error);
			exit;
		}

		$action = $_POST['op'];


		if ($action == "SIGN UP")
		{
			$username = $_POST['username'];    
			$_SESSION['username'] = $username; 
			$password = $_POST['password'];

			$stmt = $mysqli->prepare("select username, count(*) as occurs from users where username = '" . $username . "'");
			if (!$stmt){
				printf("Oops, query failed: %s\n", $mysqli->error);
				exit;
			}

			//echo "after first query";
			$stmt->execute();
			$stmt->bind_result($nullval, $occurs);
			$stmt->fetch();
			//echo $occurs;

			$stmt->close();

			if ($occurs == 0)
			{//user is not already in database
				$hash = saltyhash($password);
				//echo "Hash: " .$hash;
				//echo "Username: " . $username;
				$stmt1 = $mysqli->prepare("insert into users (username, passhash) values (?, ?)");
				if (!$stmt1){
					printf("Oops, query failed: %s\n", $mysqli->error);
					exit;
				}

				$stmt1->bind_param('ss', $username, $hash);
				$stmt1->execute();
				$stmt1->close();


				$stmt3 = $mysqli->prepare("select id from users where username = '". $username ."'");
				if (!$stmt3){
					printf("Oops, query failed: %s\n", $mysqli->error);
					exit;
				}

$stmt3->execute();
$stmt3->bind_result($userid);

$stmt3->fetch();
	$_SESSION['userid'] = $userid;
	$_SESSION['username'] = $username;
	header("Location: mainPage.php");
$stmt3->close();

			}
			else{//user has already signed up

				echo "<p> This username has already been used! If this is you, then go back and login, else go back and sign up with a new username and password </p>";
			}

		}
		else if ($action = "ENTER")
		{

			$username = "name was not set";
			if (isset($_POST['username']))
			{
				$username = $_POST['username'];
				$_SESSION['username'] = $username;
				$password = $_POST['password'];


			}
			echo $username;
			echo $password;
			$stmt2 = $mysqli->prepare("select id, username, passhash from users where username = '" . $username . "'");
			if (!$stmt2){
				printf("Oops, query failed: %s\n", $mysqli->error);
				exit;
			}

			//echo "after first query";
			$stmt2->execute();
			$stmt2->bind_result($userid, $username, $passhash);
			$stmt2->fetch();
			$stmt2->close();




			if ($username != NULL)
			{
				//echo "reached here";
				
				if (!isVerified($passhash, $password)){
					
					if (isset($_SESSION['tries']))//login attempts
					{	
						if ($_SESSION['tries'] >= 3 )
						{
							//if we exceed number of login attempts, go back to main page with noone signed in.
							$_SESSION['tries'] = 1;
							unset($_SESSION['username']);
							unset($_SESSION['userid']);
							$_SESSION['attempts'] = false;
							header("Location: mainPage.php");	
							//echo "wrongpass attmepts out of range";
				
						}
						else
						{
							$_SESSION['tries'] += 1;
							//echo $_SESSION['tries'];
							header("Location: login.html");
							//echo "wrongpass attempts in range";
						}
						
					}

					else
					{
						$_SESSION['tries'] = 1;
						header("Location: login.html");
					}
				}
				
				else
				{
					//access granted
					$_SESSION['tries'] = 0;
					$_SESSION['userid'] = $userid;
					$_SESSION['username'] = $username;
					header("Location: mainPage.php");
					//echo "access granted";	

				}
				
				
			}
			else
			{
				echo "<p> Your username has not signed up yet! Please signup. </p>";

			}	





		}//end of else if	
		else{}
		echo "<form action = \"login.html\" method = \"GET\" />
		<input type = \"submit\" value = \"Back to Login\" />
		</form>";



	?>
</body>


</html>
