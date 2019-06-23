<!DOCTYPE html>
<html lang="zh-TW">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>EZ Crawler</title>
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
		
		<script src="crawler.js"></script>
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
	require_once('crawler_db_settings.php');

	if($conn) {
		$select_sql = "SELECT * FROM `populations`";
		$quary_result = mysqli_query($conn, $select_sql);
		
        if(mysqli_num_rows($quary_result) > 0){
            for ($i = 0; $i < mysqli_num_rows($quary_result); $i++){
                $record = mysqli_fetch_row($quary_result);
                echo "<tr>";
                echo "<th scope='row'>" . $record[0] . "</th>";
                echo "<td>" . $record[1] . "</td>";
                echo "<td>" . $record[2] . "</td>";
                echo "</tr>";
            }
		}

		echo '<script>document.getElementsByClassName("status")[0].innerText = "資料載入完成，載入的資料共 '. mysqli_num_rows($quary_result) . ' 筆";</script>';
	} else {
		echo "無法連接資料庫 {$dbname}，錯誤訊息 :<br/>" . mysqli_connect_error();
	}
	
	mysqli_close($conn);
?>
				</tbody>
			</table>
		</div>
	</body>
</html>
