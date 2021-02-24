<?php

	include "admin_check.php";


	//initialize potential error message
	$msg = "";

	//initialize table
	$table = ucfirst ($_GET['view']);

	//pluralize table name
	$title = "";
	if ($table == "County")
		$title = "Counties";
	elseif ($table == "Category")
		$title = "Categories";
	else
		$title = $table . "s";

	//if no selection, initialize selection array
	if (isset($_POST['selected']) == false)
		$_POST['selected'] = array();

	foreach ($_POST['selected'] as $sel)
		print($sel . ", ");

	//if submitted
	if (isset($_POST['action']))
	{
		switch ($_POST['action'])
		{
			//add new record
			case "Add":
				include $_GET['view'] . ".php";
				exit();
				break;

			//if editing record, make sure that exactly one was selected
			case "Edit":
				if (count($_POST['selected']) === 0)
					$msg = "Please select a " . $_GET['view'] . " to edit.";

				elseif (count($_POST['selected']) === 1)
				{
					include $_GET['view'] . ".php";
					exit();
				}

				else //count > 1
					$msg = "Please select only one " . $_GET['view'] . " to edit.";

				$msg = '<font color="red">' . $msg . '</font>';
				break;

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
					$msg = '<font color="red">Please select a judge to check out.</font>';
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

			//if deleting record, confirm
			case "Delete":
				if (count($_POST['selected']) > 0) //at least 1 record selected
				{
					$deleted = 0;
					$not_deleted = array();
					//delete
					foreach ($_POST['selected'] as $id)
					{
						if ($connection->exec
						("
							delete from " . $table . "
							where " . $table . "ID = " . $id
						))
						{
							$deleted ++;
						}
						else
							array_push ($not_deleted, $connection->errorInfo()[2]); 
					}

					//remove empty elements from $not_deleted
					$not_deleted = array_diff($not_deleted, array(""));

					//get feedback from database
					$msg = $deleted . " ";
					if ($deleted === 1)
						$msg = $msg . strtolower($table);
					else
						$msg = $msg . strtolower($title);
					$msg = '<font color="green">' . $msg .  " deleted.</font><br>";

					//compose the rest of the error message
					//e.g. "Marion County could not be deleted because there is at least one school that depends on it.<br>"
					if (isset($not_deleted[0]))
					{
						$msg = '<font color="red">' . $msg . $not_deleted[0];

						if (count($not_deleted) > 1)
						{
							if (count($not_deleted) > 2)
							{
								$msg = $msg . ", ";

								for ($i = 1; $i < count($not_deleted) - 1; $i++)
									$msg = $msg . $not_deleted[$i] .	", ";
							}
							else
								$msg = $msg . " ";

							$msg = $msg . "and " . end($not_deleted);
						}

						$msg = $msg . " could not be deleted because there is at least one ";
						$dependent = "";
						switch ($table)
						{
							case "County":
								$dependent = "school ";
								break;
							case "Grade":
								$dependent = "student ";
								break;
							case "School":
								$dependent = "student "; 
								break;
							default:
								$dependent = "other entity ";
						}

						$msg = $msg . $dependent . "that depends on ";

						if (count($not_deleted) == 1)
							$msg = $msg . "it.";
						else
							$msg = $msg . "them.";

						$msg = $msg . "</font><br>";

					} //end > 1 not deleted
				} //end 1 record selected
				else //0 records selected
				{
					$msg = "Please select " . strtolower($title) . " to delete.";
				}
				break; //end case delete

		} //end switch on action
	} //end if submitted

	//get the selection to display
	$selection = "";
	$show_buttons = true; //whether add, edit, and delete are shown
	switch ($_GET['view'])
	{
		case "administrator":
			$selection = 'CONCAT
			(
				RPAD (LEFT (FirstName, 15), 16, " "),
				LEFT (     MiddleName, 1), " ",
				RPAD (LEFT (LastName,  15), 15, " ")
			)'; 

			$record_set = $connection->query
			("
				select AuthorityLevel
				from Administrator
				where AdministratorID = " . $_SESSION ['Administrator']
			);
			if ($record_set->fetch(PDO::FETCH_ASSOC)["AuthorityLevel"] !== "1")
				$show_buttons = false;
			break;
		case "booth":
			$selection = "BoothNum";
			break;
		case "category":
			$selection = "CategoryName";
			break;
		case "county":
			$selection = "CountyName";
			break;
		case "grade":
			$selection = "GradeNum";
			break;
		case "project":
			$selection = "Title";
			break;
		case "school":
			$selection = "SchoolName";
			break;
		case "session":
			$selection = 'CONCAT (TIME(StartTime), " - ", TIME(EndTime))';
			break;
		case "student":
			$selection = 'CONCAT
			(
				RPAD (LEFT (FirstName, 15), 16, " "),
				LEFT (     MiddleName, 1), " ",
				RPAD (LEFT (LastName,  15), 15, " ")
			)'; 
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
	$record_set = $connection->query
	("
		select " .
			$table . "ID as ID," .
			$selection . " as selection
		from " . $table
	);
	$records = $record_set->fetchAll();
	$record_set->closeCursor();
?>

<title><?php print ($title) ?></title>
<div class="wrapper">

<div class="main-f">
	<h1><strong><?php print ($title) ?></strong></h1>
	<?php print("<p>". $msg . "</p>") ?>
	<div class="form-s">
	<?php
		if ($_GET['view'] == 'student')
		{
			print ('
				<form action="studentUpload.php" method="post"
					<input type="file" name="file" id="file"><br>
					<input type="submit" name="submit" value="Upload Student CSV">
				</form>
			');
		}
		elseif ($_GET['view'] == 'project')
		{
			print('
				<form action="projectUpload.php" method="post"
					enctype="multipart/form-data">
					<input type="file" name="file" id="file"><br>
					<input type="submit" name="submit" value="Upload Project CSV">
				</form>
			');
		}
	?>

	<form action="Administrator.php?view=<?php print ($_GET['view']) ?>" method="post">
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
			if ($show_buttons)
			{
				if ($_GET['view'] == 'judge')
					print
					('
					<div class="view-b">
						<button type="submit" name="action" value="Checkin" class="btn">Check in</button>
						<button type="submit" name="action" value="Checkout" class="btn">Check out</button>
					</div>
					');
				else
				{
					print
					('
						<div class="view-b">
							<button type="submit" name="action" value="Add"    class="btn">Add</button>
							<button type="submit" name="action" value="Edit"   class="btn">Edit</button>
							<button type="submit" name="action" value="Delete" onclick="return confirm(\'Delete?\')" class="btn">Delete</button>
						</div>
					');
				}
			}
		?>
	</form>

	<form action="Administrator.php?view=actions" method="get" class="back-btn">
		<button type="submit">Back</button>
	</form>
</div>
</div>


