<!-- This file is to be included at the top of the page -->

<?php
	try
	{
		$connection = new PDO("mysql:host=localhost;dbname=casawils_db", "casawils", "casawils");
	}
	catch (PDOException $e)
	{
		print("<p>Error connecting to database</p><br>");
	}

	session_start();
?>

<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="style.css" type="text/css" />

<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
</head>
<body>
<div id="container">

    <header>
        	<div class="width">

		    <nav>
			<img src="image.png" alt="i" class="img-nav">
    			<ul class="sf-menu dropdown">

		<li><a href="http://corsair.cs.iupui.edu:24601/home.php">Home</a></li> 



<?php


	if (isset($_SESSION ['Judge'])) {
		echo '<li><a href="http://corsair.cs.iupui.edu:24601/schedule.php">Schedule</a></li>';
	}
	else { 
		echo '<li><a href="http://corsair.cs.iupui.edu:24601/Judge.php">Judge login</a></li>';
   }

	if (!isset($_SESSION ['Administrator'])) { 
		echo '<li><a href="http://corsair.cs.iupui.edu:24601/Admin.php">Admin login</a></li>';
   }


?>

            	<li><a href="http://corsair.cs.iupui.edu:24601/register.php">Register</a></li>
		<li><a href="#">Contact</a></li>

<?php

	if (isset($_SESSION ['Judge']) || isset($_SESSION ['Administrator']) ) { 
	echo '<li><a href="logout.php">logout</a></li>';
   }


?>

        </ul>

	
<!--
    			<ul class="sf-menu dropdown">
        	<li><a href="#">Home</a></li>
 
            <li><a class="has_submenu" href="#">login</a>
            	<ul>
                	<li><a href="http://corsair.cs.iupui.edu:24601/Judge.php">Judge Login</a></li>
                    <li><a href="http://corsair.cs.iupui.edu:24601/Administrator.php">Admin Login</a></li>
                </ul>
            </li>
            <li><a href="http://corsair.cs.iupui.edu:24601/register.php">Register</a></li>
	   <li><a href='./schedule.php'>Schedule</a></li>
			
            <li><a href="#">Contact</a></li>
        </ul>
-->
    			</nav>
       	</div>

	<div class="clear"></div>

    </header>
