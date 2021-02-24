<?php

	include "admin_check.php";

	$msg = "";

	if (isset ($_POST['Judge']))
	{
		$query = $connection->prepare
		("
			update Judge
			set Active = 1
			where JudgeID = " . $_POST['Judge']
		);

		if ($query->execute())
		{
			$msg = '<font color="green">Successfully checked in judge.</font><br>';
		}
		else $msg = '<font color="red">Please select a judge.</font><br>';
	}

?>

<title>Judge Checkin</title>
<div class="wrapper">

<div class="main-f">
	<h1><strong>Judge Checkin</strong></h1>
	<div class="form-s">
		<form action="Administrator.php?view=checkin" method="post">

		<?php print($msg) ?>

			<!-- Mark As attending -->
			<label for="Judge">Select judge</label>
			<select name="Judge" id="Judge">
				<option value=# selected></option>
			<?php
				$record_set = $connection->query
				("
					select JudgeID, FirstName, LastName, Username
					from Judge
				");
				$judges = $record_set->fetchAll();

				foreach ($judges as $judge)
				{
					print
					(
						"<option value=" . $judge['JudgeID'] . ">" .
						$judge['FirstName'] . " " . $judge['LastName'] .
						" (" . $judge['Username'] . ")</option>"
					);
				}
			?>
			</select><br>

			<button type="submit" class="btn">Check In</button>

		</form>


		</form>

	<form action="Administrator.php" method="post">
		<button type="submit">Back</button>
	</form>

	</div>
</div>
</div>
