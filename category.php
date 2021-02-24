<?php
	include "admin_check.php";

	//initialize category
	$category = array();

	//make an array to keep track of potential error messages
	$msgs = array();
	$msg  = "";

	//if data has been submitted, prefill with that data
	if (isset($_POST['CategoryName']))
	{
		//copy POST data
		$booth = array('CategoryName' => $_POST['CategoryName']);

		$msgs = array('CategoryName' => "");

		/* CategoryName Validity and Uniqueness */
		//validity
		if ($category['CategoryName'] == "")
			$msgs['CategoryName'] = "Category Name cannot be empty.";

		//uniqueness
		else
		{
			$unique_check = "";
			if ($_POST['action'] == "Edit")
				$unique_check = " AND CategoryID != " . $_POST['selected'][0];

			$query = $connection->prepare
			("
				select count(*) as 'count'
				from Category
				where CategoryName = ?" .
				$unique_check
			);
			$query->execute(array($category['CategoryName']));
			$count = $query->fetch(PDO::FETCH_ASSOC)['count'];

			if ($count !== "0")
				$msgs['CategoryName'] = "There is already a category with this name.";
		} //end CategoryName uniqueness

		//if no error messages, update database
		$good_input = true;
		foreach ($msgs as &$msg)
		{
			if ($msg != "")
			{
				$good_input = false;

				//formatting
				$msg = '<font color="red">'. $msg . "</font><br>";
			}
		}
		unset($msg);

		if ($good_input)
		{
			if ($_POST['action'] == "Add")
			{
				$query = $connection->prepare
				("
					insert into
						Category (CategoryName)
						values (?)
				");
			}

			elseif ($_POST['action'] == "Edit")
			{
				//append ID to category
				$category["ID"] = $_POST['selected'][0];
				$query = $connection->prepare
				("
					update Category
					set CategoryName = ?
					where CategoryID = ?
				");
			}

			//execute the sql statement
			$query->execute(array_values($booth));


			//confirmation message
			$msg = '<font color="green">Successfully ';
			
			if ($_POST['action'] == "Add")
				$msg = $msg . "added ";
			elseif ($_POST['action'] == "Edit")
				$msg = $msg . "changed name to ";

			$msg = $msg . $category['CategoryName'] . "</font><br>";

			//clear fields for next entry
			if ($_POST['action'] == "Add")
				$category = array_fill_keys (array_keys($category), "");

		} //end if good input

		//if bad input
		else $msg = "";

	} //end if data submitted

	elseif ($_POST['action'] == "Edit")
	{
		$record_set = $connection->query
		("
			select CategoryName
			from Category
			where CategoryID = " . $_POST['selected'][0]
		);
		$category = $record_set->fetch (PDO::FETCH_ASSOC);
	}
	elseif ($_POST['action'] == "Add")
	{
		$category = array ("CategoryName" => "");
	}

?>

<title><?php print($_POST['action'] . " ") ?>Category</title>
<div class="wrapper">

<div class="main-f">
	<h1><strong><?php print($_POST['action']) ?> Category</strong></h1>
	<div class="form-s">
		<form action="Administrator.php?view=category" method="post" >

			<?php print($msg) ?>

			<div class="label">
				<label for="CategoryName">Category Name:</label>
				<input
					type="text"
					name="CategoryName"
					value="<?php print ($category["CategoryName"])?>"
				><br>
			</div>

			<?php
				if (isset ($msgs['CategoryName']))
					print($msgs['CategoryName']);

				if (isset($_POST['selected'][0]))
					print ('<input type="hidden" name="selected[]" value="' .
						$_POST['selected'][0] . '">'); 
			?>

			<button type="submit" name="action"
				value="<?php print($_POST['action']) ?>" class="btn">Submit</button>

		</form>

	</div>

	<form action="Administrator.php?view=category" method="post" class="back-btn">
		<button type="submit">Back</button>
	</form>

</div>
</div>
