<?php
	require_once('crawler_db_settings.php');
	
	if($_POST['reGetData']){
		if($conn) {
			$select_sql = "SELECT * FROM `populations`";
			$quary_result = mysqli_query($conn, $select_sql);
			
			if(mysqli_num_rows($quary_result) > 0) {
				$truncate_sql = 'TRUNCATE populations';
				$result = mysqli_query($conn, $truncate_sql);
			}
			
			$html = file_get_contents($url);
			$dom = new DOMDocument();
			@$dom->loadHTML($html);
			$dom->preserveWhiteSpace = false;
			
			$tables = $dom->getElementsByTagName('table');
			
			$count = 0;
			foreach ($tables as $table) {
				$tds = $table->getElementsByTagName('td');
				$i = 0;
				$output = '';
				foreach ($tds as $td) {
					$i++;
					switch ($i) {
						case 1:
							$output .= "<tr><th scope='row'>" . ($count + 1) . "</th>";
							break;
						case 2:
							$country_name = trim($td->nodeValue);
							$output .= "<td>" . $country_name . "</td>";
							break;
						case 3:
							$population = str_replace(',', '', trim($td->nodeValue));
							$output .= "<td>" . $population . "</td></tr>";
							break;
						case 6:
							$density = str_replace(',', '', trim($td->nodeValue));
							break;
						case 7:
							$land_area = str_replace(',', '', trim($td->nodeValue));
							
							$insert_sql = 'INSERT INTO populations(country_name, population, density, land_area) VALUES("%s","%s","%s","%s")';
							$SQL = sprintf($insert_sql, $country_name, $population, $density, $land_area);
							$result = mysqli_query($conn, $SQL);
							break;
						default: break;
					}
					
					if($i%12 == 0) {
						$i = 0;
						$count++;
					}
				}
				/**
				 * not really need
				 * 
				if($count == 233) {
					//break;
				}
				 */
			}
			
			mysqli_close($conn);
			echo $output;
			echo '<script>document.getElementsByClassName("status")[0].innerText = "資料載入完成，載入的資料共 '. $count . ' 筆";</script>';
			
		} else {
			echo 'fail';
		}
	}
?>
