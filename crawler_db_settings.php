<?php
	$url = "https://www.worldometers.info/world-population/population-by-country/";
	
	$host = 'localhost';
	$dbuser = 'root';
	$dbpw = '';
	$dbname = 'crawler_db';
	
	$conn = new mysqli($host, $dbuser, $dbpw, $dbname);
	
    if ($conn->connect_errno) {
        echo "Error: Unable to connect to MySQL.<br>";
        echo "Debugging errno: " . $conn->connect_errno . "<br>";
        echo "Debugging error: " . $conn->connect_error . "<br>";
        exit;
    }
    
    $conn->query("SET CHARACTER SET UTF8");
