<!--
	rankings.php

	Get average judge rankings for each project by judge.
-->

<?php

	include "connect.php";

	$records = $connection->query ("select * from AverageRankings");
	$projects = $records->fetchAll (PDO::FETCH_ASSOC);
