<?php
	
	include "database.php";
	
	function checkPassword($inputUserID, $inputPassword)
	{
		$passwordDatabase = getPassword($inputUserID);
		$passwordUser = crypt($inputPassword, 'df)v.M#ypcC1');

		if(strcmp($passwordDatabase, $passwordUser)==0)
		{
			return true;
		}
		return false;
	}

	function loginUser($inputEmail, $inputPassword)
	{
		$userID = getUserID($inputEmail);
		if($userID != null){
			if (checkPassword($userID, $inputPassword)) {
				session_start();
				header("Location: http://employium.atwebpages.com/home.html");
				return true;
			}
		}
		echo "Login Failed";
		return false;
	}

?>