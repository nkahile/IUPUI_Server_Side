<?php	include "judge_check.php" ?>

<!-- included by Admin.php -->
<title>Judge Actions</title>
<div class="wrapper">

<div class="main-f">
	<h1><strong>Judge Actions</strong></h1>
	<div class="admin-action">	

			<form name = "checkin" action = "Judge.php?action=checkin" method = "post">
				<button type = "submit" name = "Judge" value = true>Check in</button>
			</form>

			<form name = "score" action = "Judge.php?action=score" method = "post">
				<button type = "submit" name = "Judge" value = true>Score a project</button>
			</form>

	</div>

</div>
</div>
