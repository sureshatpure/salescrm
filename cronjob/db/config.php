<?php
		$hostname="10.1.0.21";
		$dbname="softcodetest";
		$user="puredb";
		$password="pure123";

  		$conn = pg_connect("host=10.1.0.21 port=5432 dbname=softcodetest user=puredb password=pure123")
      					or die ("Not able to connect to PostGres --> " . pg_last_error($conn));
?> 
