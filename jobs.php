<?php
        include "database.php";
		
		// Search for vacancies with matching skills
		// Returns all information to display on UI in the form of a 2D array
		function searchVacanciesWithAllInfo($skills1, $skills2, $skills3){
			
			$output = array();
			// Search for all vacancies 
			$vacancyIDs = searchVacancies($skills1, $skills2, $skills3);
			if(!empty($vacancyIDs)){
				foreach($vacancyIDs as $vacancy){
					$vacancyInfo = array();
					$query = "SELECT CompanyID, MinSalary, MaxSalary FROM vacancies WHERE VacancyID = " . "\"". $vacancy["VacancyID"] . "\"";
					$result = executeQuery($query);
					$companyName = ""; 
					$minSalary = ""; 
					$maxSalary = "";
					
					if($result->num_rows > 0){
						$result = $result->fetch_assoc();
						$query = "SELECT Name FROM company_profile WHERE UserID = " . "\"" . $result["CompanyID"] . "\"";
						$companyName = executeQuery($query);
						$companyName = $companyName->fetch_assoc()["Name"];
						
						$minSalary = $result["MinSalary"];
						$maxSalary = $result["MaxSalary"];
					}
					
					array_push($vacancyInfo, $companyName);
					array_push($vacancyInfo, $minSalary);
					array_push($vacancyInfo, $maxSalary);
					
					$skills = getRequiredSkills($vacancy["VacancyID"]);
					foreach($skills as $skill){
						array_push($vacancyInfo, $skill);
					}
					array_push($output, $vacancyInfo);
				}
			}
			return $output;
			
		}
?>