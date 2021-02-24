<!-- included in judge page files -->

<?php
	//check whether judge is logged in
	if (!isset ($_SESSION ['Judge']))
	{
		header ("Location: Judge.php");
		exit();
	}
?>
