<!--

	Administrator.php

	Administrator is a class that inherits from Entity, overriding abstract methods
	to achieve polymorphic behavior.

-->

<?php

include "Entity.php";

class Administrator extends Entity
{
	//constructor
	function __construct ($connection)
	{
		//initialize $table, $title, $view, and $connection
		parent::__construct ($connection);

		//empty fields
		$this->fields = array
		(
			"FirstName"      => "",
			"MiddleName"     => "",
			"LastName"       => "",
			"Email"          => "",
			"Username"       => "",
			"Password"       => "",
			"pass_conf"      => "",
			"AuthorityLevel" => ""
		);

	} //end constructor

	/* Override abstract methods */
	//select identifying data from records
	public function display_data()
	{
		//get records
		$record_set = $this->connection->query
		("
			select
				AdministratorID as ID,
				CONCAT (
					LEFT (FirstName, 15), ' ',
					LEFT (MiddleName, 1), '. ',
					LEFT (LastName,  15)
				) as selection
				from Administrator
		");
		$records = $record_set->fetchAll();
		$record_set->closeCursor();

		//return records
		return $records;

	} //end function display_data();

	//only show action buttons if the administrator has sufficient authority
	public function buttons()
	{
		//if authority level is 1, show buttons
		if ($this->get_authority() === '1')
			return parent::buttons();

		//insufficient authority
		else
		{
			$button = 
				'<div class="view-b">
					<button type="submit" name="action" value="Edit" class="btn"
						>View</button>
				</div>'
			;

			return $button;

		} //end insufficient authority
	
	} //end function buttons()

	//check whether data has been submitted
	protected function submitted ($post)
	{
		return isset ($post["FirstName"]);
	}

	//validate field entries and update msgs array
	protected function validate (&$msgs, $original)
	{
		//set empty original to NULL if adding
		if ($original == false)
			$original = "NULL";

		//assume input valid
		$valid = true;

		//first
		if ($this->fields['FirstName'] == "")
		{
			$valid = false;
			$msgs['FirstName'] = "First name cannot be empty.";
		}

		//no one cares about middle name

		//last
		if ($this->fields['LastName'] == "")
		{
			$valid = false;
			$msgs['LastName'] = "Last name cannot be empty.";
		}

		//email validity
		if ($this->fields['Email'] == "")
		{
			$valid = false;
			$msgs['Email'] = "Email cannot be empty.";
		}
			
		elseif (filter_input (INPUT_POST, "Email", FILTER_VALIDATE_EMAIL) == false)
		{
			$valid = false;
			$msgs['Email'] = "Email is invalid.";
		}

		//email uniqueness
		if ($this->fields['Email'] != "")
		{
			$query = $this->connection->prepare
			("
				select count(*) as 'count'
				from Administrator
				where Email = ? AND
				NOT AdministratorID <=> " . $original //NULL-safe equals operator
			);
			$query->execute(array($this->fields['Email']));
			$count = $query->fetch(PDO::FETCH_ASSOC)['count'];

			//if email taken
			if ($count !== "0")
			{
				$valid = false;
				$msgs['Email'] = "Another administrator already has this email.";

			} //end if email taken

		} //end email uniqueness

		//username validity
		if ($this->fields['Username'] == "")
		{
			$valid = false;
			$msgs['Username'] = "Username cannot be empty.";
		}

		//username uniqueness
		elseif ($this->fields['Username'] != "")
		{
			$query = $this->connection->prepare
			("
				select count(*) as 'count'
				from Administrator
				where Username = ? AND
				NOT AdministratorID <=> " . $original //NULL-safe equals operator
			);
			$query->execute(array($this->fields['Username']));
			$count = $query->fetch(PDO::FETCH_ASSOC)['count'];

			//if username taken
			if ($count !== "0")
			{
				$valid = false;
				$msgs['Username'] =
					"There is already an administrator with this username."
				;
			} //end if username taken

		} //end username uniqueness check

		//if adding, require password
		if ($original == "NULL")
		{
			//password
			if ($this->fields['Password'] == "")
			{
				$valid = false;
				$msgs['Password'] = "Password cannot be empty.";
			}
/*
			//password confirmation doesn't match
			if ($this->fields['pass_conf'] != $this->fields['Password'])
			{
				$valid = false;
				$msgs['pass_conf'] = "Passwords don't match.";
			}
*/

		} //end if adding

		//password confirmation
		if ($this->fields['pass_conf'] != $this->fields['Password'])
		{
			$valid = false;
			$msgs['pass_conf'] = "Passwords don't match.";
		}

		return $valid;
	
	} //end function validate()

	//Administrator has no dropdown options
	protected function get_options()
	{}

	//insert data from fields array into database
	protected function insert()
	{
		//pass_conf is redundant
		unset ($this->fields['pass_conf']);

		$query = $this->connection->prepare
		("
			insert into
				Administrator (FirstName, MiddleName, LastName, Email, Username, Password, AuthorityLevel)
				values        (        ?,          ?,        ?,     ?,        ?,        ?,              ?)
		");

			foreach ($this->fields as $k => $v)
				print ($k . " => " . $v . "<br>");
	
		$query->execute (array_values($this->fields));

	} //end function insert

	//update database with data from fields array
	protected function update()
	{
		//password confirmation is redundant
		unset ($this->fields['pass_conf']);

		//begin building query
		$query_text =
		"
			update Administrator
			set
				FirstName = ?,
				MiddleName = ?,
				LastName = ?,
				Email = ?,
				Username = ?,
		";

		//if password is empty, ignore it
		if ($this->fields['Password'] == "")
			unset ($this->fields ['Password']);

		//if password not empty, include it
		else
		{
			$query_text .=
			"
				Password = ?,
			";
		}

		//finish building query
		$query_text .=
		"
				AuthorityLevel = ?
			where AdministratorID = ?
		";

		$query = $this->connection->prepare($query_text);
		$query->execute (array_values($this->fields));

	} //end function update()

	//confirm an add operation
	protected function confirm_add()
	{
		$msg =
			$this->fields['FirstName'] . " " . $this->fields['LastName'] .
			" added as administrator."
		;

		return '<font color="green">' . $msg . '</font><br>';

	} //end function confirm_add()

	//confirm an edit operation
	protected function confirm_edit()
	{
		$msg =
			$this->fields['FirstName'] . " " . $this->fields['LastName'] .
			" successfully modified."
		;

		return '<font color="green">' . $msg . '</font><br>';

	} //end function confirm_edit()

	//set fields array to columns and values of target record from database
	protected function prefill ($target)
	{
		$record_set = $this->connection->query
		("
			select
				FirstName,
				MiddleName,
				LastName,
				Email,
				Username,
				Password,
				AuthorityLevel
			from Administrator
			where AdministratorID = " . $target
		);

		$this->fields = $record_set->fetch (PDO::FETCH_ASSOC);

		//clear password fields
		$this->fields ['Password'] = "";
		$this->fields ['pass_conf'] = "";

	} //end function prefill()

} //end class Administrator

?>
