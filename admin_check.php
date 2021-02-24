<!-- included in admin page files -->

<?php
	//check whether admin is logged in
	if (!isset ($_SESSION ['Administrator']))
	{
		header ("Location: Administrator.php");
		exit();
	}
?>
