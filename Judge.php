<?php
	require_once "header.php";
	require_once "footer.php";

	//check whether judge is logged in
	if (isset ($_SESSION ['Judge']))
	{
		//display the file associated with the chosen action
		include "schedule.php";
	}

	//if the judge is not logged in yet, make them log in
	else
	{
		$_SESSION['user_type'] = "Judge";
		include "login1.php";
	}
?>
