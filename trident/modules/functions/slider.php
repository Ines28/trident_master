<?php

	class modSliderClass extends QueryClass{


		function getSlides(){
		
			$this->serverConnection();
			
			$today = date("Y-m-d H:i:s");
			
			$sqlSlides = "SELECT id, url, target, date_to, title, subtitle
						  FROM slider
						  WHERE published = 1
						  AND date_from <= '$today'
						  ORDER BY ordering ASC";
						  
			$slides_list = $this->query($sqlSlides, "list");
			$slides = array();
			
			while($row = mysql_fetch_row($slides_list)){
				
				$slides[] = array(
									"id" => $row[0],
									"url" => $row[1],
									"target" => $row[2],
									"date_to" => $row[3],
									"title" => $row[4],
									"subtitle" => $row[5],
									"ordering" => $row[6]
								);
				
			}
			
			mysql_close($this->_connection);
			
			return $slides;
		}
		
		
		
		
		
		
		
		function getLeadPicture($slideID){
		
			$this->serverConnection();
			
			$sqlPicture = "SELECT id, file
						   FROM slider_pictures
						   WHERE slide = $slideID
						   AND cover = 1
						   LIMIT 1";
			
			return $this->query($sqlPicture, "select");
			mysql_close($this->_connection);
			
		
		}
		
	
	
	}

?>