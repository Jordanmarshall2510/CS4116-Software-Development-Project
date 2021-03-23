<?php	
		// Connect to our database
		function openConnection() {
			// Connection details
			$servername = "fdb30.awardspace.net";
			$username = "3773072_employium";
			$password = "Tester123";
			$dbname = "3773072_employium";
			
			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);
			// Check connection
			if ($conn->connect_error) {
			  die("Connection failed: " . $conn->connect_error);
			  echo "Connection failed";
			}
			return $conn;
		}
		
		// Close connection to our database
		function closeConnection($conn) {
			mysqli_close($conn);
		}
		
		// Execute the passed SQL query and return a MYSQLI_RESULT object
        function executeQuery($query){
			$conn = openConnection();
			
			// Execute the query
			$result = $conn->query($query);
			
			// Check if the result is empty
			if ($result->num_rows == 0) {
				echo "No results for query: " . $query;
			}
			
			closeConnection($conn);
			return $result;
		}
		
		// Get all connections for a given user and return them as an array
		function getConnections($user){
			// Execute the query
			$query = "SELECT UserB_ID FROM connections where UserA_ID = " . $user;
			$result = executeQuery($query);
			
			$connections = array();
			
			// Save the results in an array
			foreach($result as $row){
				array_push($connections, $row["UserB_ID"]);
			}
			
			return $connections;
		}
		
		// Get all vacancies for a given company and return them as an array
		function getVacancies($company){
			// Execute the query
			$query = "SELECT VacancyID FROM vacancies where CompanyID = " . $company;
			$result = executeQuery($query);
			
			$vacancies = array();
			
			// Save the results in an array
			foreach($result as $row){
				array_push($vacancies, $row["VacancyID"]);
			}
			
			return $vacancies;
		}
		
		// Search vacancies based on required skills
		function searchVacancies($skillA, $skillB, $skillC){
			
			// Get all VacancyID's
			$query = "SELECT VacancyID FROM vacancies";
			$result = executeQuery($query);
			
			$matchingVacancies = array();
			
			// Calculate required "skillMatches" value for a Vacancy to match search criteria
			// This essentially figures out the number of skills we have given as search criteria.
			// For example, if $skillB and $skillC are empty, $reqVacancyMatches would result to 1.
			// This means we only have to find 1 matching skill in the Vacancies required skills
			// in order for that vacancy to match the search criteria.
			$reqVacancyMatches = 0;
			if( !empty($skillA) ){
				$reqVacancyMatches += 1;
			}
			if( !empty($skillB) ){
				$reqVacancyMatches += 1;
			}
			if( !empty($skillC) ){
				$reqVacancyMatches += 1;
			}
			
			// Get the required skills from each Vacancy
			foreach($result as $vacancyID){
				$requiredSkills = getRequiredSkills($vacancyID["VacancyID"]);
				
				$actVacancyMatches = 0;
				// Check if the searched skills match the required skills
				foreach($requiredSkills as $skill){
					
					// Each time we find a matching skill, increment the $actVacancyMatches value by 1.
					// If we repeat this process, only vacancies with skills that match our search
					// criteria will end up with the $reqVacancyMatches value we found earlier.
					// Note: This assumes that Vacancies are required to have all 3 required skills
					// populated with no null values.
					if( (strcmp( $skill, $skillA ) ) == 0 ){
						$actVacancyMatches++;
					}
					if( (strcmp( $skill, $skillB ) ) == 0 ){
						$actVacancyMatches++;
					}
					if( (strcmp( $skill, $skillC ) ) == 0 ){
						$actVacancyMatches++;
					}
				}
				
				// If these values match, it means that we have found a vacancy
				// which matches the given search criteria.
				// Push this vacancy to the array of matchingVacancies.
				if($actVacancyMatches == $reqVacancyMatches){
					array_push($matchingVacancies, $vacancyID);
				}
			}
			
			return $matchingVacancies;
		}
		
		// Get the required skills from a job vacancy
		function getRequiredSkills($vacancyID){
			// Execute the query
			$query = "SELECT RequiredSkills FROM vacancies WHERE VacancyID = " . $vacancyID;
			$result = executeQuery($query);
			
			$requiredSkills = array();
			
			// Save the results in an array
			foreach($result as $row){
				$requiredSkills = explode(", ", $row["RequiredSkills"], 3);
			}
			
			return $requiredSkills;
		}
		
		// Get all users with a similar name to the input
		function searchUsers($name){
			$query = "SELECT Name, Occupation from profile WHERE Name LIKE '%".$name."%'";
			$result = executeQuery($query);
			return $result;
		}

		function getUserID($email)
		{
			$query = "SELECT UserID from account_information WHERE Email = \"". $email."\"";
			$result = executeQuery($query);
			if ($result->num_rows > 0) {
				$result = $result->fetch_assoc()["UserID"];
			}
			else
			{
				$result = null;
			}
			return $result;
		}

		function getPassword($userID)
		{
			$query = "SELECT Password from account_information WHERE UserID = \"".$userID."\"";
			$result = executeQuery($query);
			if ($result->num_rows > 0) {
				$result = $result->fetch_assoc()["Password"];
			}
			else
			{
				$result = null;
			}
			return $result;
		}

		//Checks is random generated user ID is taken or not.
		function isUserIDTaken($randomID)
		{
			$query = "SELECT UserID FROM account_information WHERE UserID = \"".$randomID."\"";
			$result = executeQuery($query);
			if ($result) 
			{
				return true;
			}
			return false;
		}
?>