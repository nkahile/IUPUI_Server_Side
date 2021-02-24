<?php

	include "admin_check.php";

	//initialize concrete Entity
	$classname = ucfirst($_GET['view']);
	include $classname . ".php";
	$entity = new $classname ($connection);

	//initialize potential error message
	$msg = "";

	//if submitted
	if (isset($_POST['action']))
	{
		//do the desired action on the desired records of the entity
		$msg = $entity->$_POST['action']($_POST);

		switch ($_POST['action'])
		{
			case "Checkin":
				if (count($_POST['selected']) === 0)
					$msg = '<font color="red">Please select a judge to check in.</font>';
				else
				{
					$updated = 0;
					foreach ($_POST['selected'] as $id)
					{
						if ($connection->exec
						("
							update Judge
							set Active = 1
							where JudgeID = " . $id
						))
						{
							$updated ++;
						}
					}
					$s = "";
					if (count($_POST['selected']) > 1)
						$s = "s";

					$msg = '<font color="green">' . $updated . ' judge' . $s . ' checked in.</font><br>';
				}
				break;

			case "Checkout":
				if (count($_POST['selected']) === 0)
				{
					$msg =
						'<font color="red"
							>Please select a judge to check out.<
						/font>'
					;
				}
				else
				{
					$updated = 0;
					foreach ($_POST['selected'] as $id)
					{
						if ($connection->exec
						("
							update Judge
							set Active = 0
							where JudgeID = " . $id
						))
						{
							$updated ++;
						}
					}
					$s = "";
					if (count($_POST['selected']) > 1)
						$s = "s";

					$msg = '<font color="green">' . $updated . ' judge' . $s . ' checked out.</font><br>';
				}
				break;

		} //end switch on action

	} //end if submitted

	//get the selection to display
	$selection = "";
	$show_buttons = true; //whether add, edit, and delete are shown
	switch ($_GET['view'])
	{
		case "category":
			$selection = "CategoryName";
			break;
		case "county":
			$selection = "CountyName";
			break;
		case "grade":
			$selection = "GradeNum";
			break;
		case "ranking":
			$selection = 'CONCAT
			(
				RPAD (ProjectNum, 3, " "), " ",
				RPAD (LEFT (Title, 15), 16, " "),
				AvgRank
			)'; 
			$show_buttons = false;
			break;
		case "judge":
			$selection = 'CONCAT (Title, " ", FirstName, " ", LastName, " ", Active)';
			break;
	} //end switch on view

	//get records
	$records = $entity->display_data();
/*
	$record_set = $connection->query
	("
		select " .
			$table . "ID as ID," .
			$selection . " as selection
		from " . $table
	);
	$records = $record_set->fetchAll();
	$record_set->closeCursor();
*/
?>

<title><?php print ($entity->title) ?></title>
<div class="wrapper">

<div class="main-f">
	<h1><strong><?php print ($entity->title) ?></strong></h1>
	<?php print("<p>". $msg . "</p>") ?>
	<div class="form-s">
	<?php
		//show buttons to upload csv
		print($entity->upload_button());
	?>

	<form action="Admin.php?view=<?php print ($_GET['view']) ?>" method="post">
		<div class="data-box">
		
<script>
function isChecked(elem) {
    elem.parentNode.style.background = (elem.checked) ? '#ffa500' : 'none';


}</script>
			<?php
			//show records
			//e.g. <input type="checkbox" name="selected" value=1 checked>Caleb Wilson</input><br>

				foreach ($records as $record)
				{
					print
					('
						<label id="data">
						<input 
						    id="change"
							type="checkbox"
							name="selected[]"
							class="check-d";
							onchange="isChecked(this)"
							value=' . $record['ID']
					);


					//preserve checkedness
					if (isset ($_POST['selected']))
					{
						if (in_array($record['ID'], $_POST['selected']))
							print (" checked");
					}

					print
					('
						>  ' .
							$record['selection'] . '
						</input></label></><br>
					');
				}	
			?>

		</div>
		<?php
			print($entity->buttons());
/*
			if ($show_buttons)
			{
*/
				if ($_GET['view'] == 'judge')
					print
					('
					<div class="view-b">
						<button type="submit" name="action" value="Checkin" class="btn">Check in</button>
						<button type="submit" name="action" value="Checkout" class="btn">Check out</button>
					</div>
					');
	?>
	</form>

	<form action="Admin.php?view=actions" method="get" class="back-btn">
		<button type="submit">Back</button>
	</form>
</div>
</div>
