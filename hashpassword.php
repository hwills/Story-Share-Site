


<?php
	
function saltyhash($password){
	$cost = 10;
	
	$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');

	$salt = "$2a$%02d$" . $cost . $salt;

	$shpass = crypt($password, $salt);
	
	return $shpass;
}

function isVerified($hash, $password){
	
	return strcmp($hash, crypt($password, $hash)) == 0;

}




?>
