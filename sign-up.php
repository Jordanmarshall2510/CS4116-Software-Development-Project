<?php
	
	include "database.php";
	
	//Validates inputted email for '@' symbol
	function validEmail($passwordString)
	{
		if (str_contains($passwordString, '@')) 
		{ 
    		return true;
		}
		return false;
	}

	//Generates unique User ID
	function generateUserID()
	{
		$generatedUserID = rand(100000000,999999999);
		while (isUserIDTaken($generatedUserID)) 
		{
			$generatedUserID = rand(100000000,999999999);
		}
		return $generatedUserID;
	}

	function isCompany()
	{

	}

	function signUpUser($name, $inputEmail, $inputPassword, $isCompany)
	{
		$userID = getUserID($inputEmail);
		if($userID != null)
		{
			if (checkPassword($userID, $inputPassword))
			{
				session_start();
				header("Location: http://employium.atwebpages.com/home.html");
				return true;
			}
		}
		echo "Sign-Up Failed";
		return false;
	}

?>