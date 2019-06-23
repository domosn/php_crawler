<!DOCTYPE html>
<html lang="zh-TW">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>爬蟲程式</title>
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
		
		<style>
            * {
                font-family:微軟正黑體;
                margin:0px;
                border:0px;
                padding:0px;
            }
			*,
			*:before,
			*:after {
				-moz-box-sizing: border-box;
				-webkit-box-sizing: border-box;
				box-sizing: border-box;
			}
			div.table-responsive {
				max-width: 1300px;
				margin: auto;
			}
			div.margin-auto {
				margin:auto;
			}
			div.mg-down {
				margin:10px 0;
			}
        </style>
	</head>
	<body>
		<div class="table-responsive">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12 margin-auto">
						<h2 class="status">資料載入中...</h2>
					</div>
				</div>
				<div class="mg-down"><button type="button" class="dataRefresh btn btn-primary">重新載入資料</button></div>
			</div>
			
			<table class="table table-hover">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">國家</th>
						<th scope="col">人口</th>
					</tr>
				</thead>
				<tbody>
<?php
	$url = "https://www.worldometers.info/world-population/population-by-country/";
	
	$host = 'localhost';
	$dbuser = 'root';
	$dbpw = '';
	$dbname = 'crawler_db';
	
	$conn = mysqli_connect($host, $dbuser, $dbpw, $dbname);
	
	if($conn) {
		$select_sql = "SELECT * FROM `populations`";
		$quary_result = mysqli_query($conn, $select_sql);
		
		if(mysqli_num_rows($quary_result) > 0) {
			$truncate_sql = 'TRUNCATE populations';
			$result = mysqli_query($conn, $truncate_sql);
		}
	}
	
	$html = file_get_contents($url);
	//echo $html;
	$dom = new DOMDocument();
	@$dom->loadHTML($html);
	$dom->preserveWhiteSpace = false;
	
	$tables = $dom->getElementsByTagName('table');
	
	$count = 0;
	foreach ($tables as $table) {
		$tds = $table->getElementsByTagName('td');
		$i = 0;
		foreach ($tds as $td) {
			$i++;
			switch ($i) {
				case 1:
					echo "<tr>";
					echo "<th scope='row'>" . ($count + 1) . "</th>";
					break;
				case 2:
					$country_name = trim($td->nodeValue);
					echo "<td>" . $country_name . "</td>";
					break;
				case 3:
					$population = str_replace(',', '', trim($td->nodeValue));
					echo "<td>" . $population . "</td>";
					echo "</tr>";
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
		
		if($count == 233) {
			//break;
		}
	}
	
	mysqli_close($conn);
	echo '<script>document.getElementsByClassName("status")[0].innerText = "資料載入完成，載入的資料共 '. $count . ' 筆";</script>';
?>
				</tbody>
			</table>
		</div>
	</body>
</html>
