<?php
        include "database.php";
		
		// Search for users with a similar name to input and return the information to display on the search page
		function searchUsersWithAllInfo($name){
			
			$output = array();
			$users = searchUsers($name);
			if($users->num_rows > 0){
				foreach($users as $user){
					$userInfo = [$user["Name"], $user["Occupation"]];
					array_push($output, $userInfo);
				}
			}
			return $output;
			
		}
?>