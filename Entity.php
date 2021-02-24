<!--

	Entity.php

	Entity is an abstract class designed to be a prototype for any entity whose
	data will need to be viewed on the website. There will be default definitions
	for most methods, but some methods will be up to the concretions to define.

-->

<?php

abstract class Entity
{
	//names
	protected $table; //table name
	public  $title; //title of web page
	protected $view;  //name of view

	//database connection
	protected $connection;

	//name of entity that is dependent on this
	protected $dependent;

	//associative array of fields and values
	protected $fields;

	//constructor
	protected function __construct ($connection)
	{
		//get name attributes from class name
		$this->table = get_class($this);
		$this->title = $this->table . 's';
		$this->view  = strtolower ($this->table);

		//get database connection
		$this->connection = $connection;

		//dependents
		$this->dependents = "other entity";

	} //end function __construct()

	/*
		select identifying data from records

		Must return an associative array of length 2 mapping column => value, with
		the first column being the primary key of the concrete Entity's table,
		selected as 'ID', and the second column being an identifying string to
		display, selected as 'selection'. The code in view.php depends upon this
		format.
	*/
	abstract public function display_data();

	//show upload buttons if necessary
	public function upload_button()
	{
		return "";
	}

	//show action buttons
	public function buttons()
	{
		$buttons =
			'<div class="view-b">
				<button type="submit" name="action" value="Add" class="btn"
					>Add</button>

				<button type="submit" name="action" value="Edit" class="btn"
					>View/Edit</button>

				<button type="submit" name="action" value="Delete"
						onclick="return confirm(\'Delete?\')" class="btn"
					>Delete</button>
			</div>'
		; //end $buttons assignment

		return $buttons;

	} //end function buttons();

	//get admin's authority level
	protected function get_authority()
	{
		$record_set = $this->connection->query
		("
			select AuthorityLevel
			from Administrator
			where AdministratorID = " . $_SESSION['Administrator']
		);

		$admin = $record_set->fetch (PDO::FETCH_ASSOC);

		return $admin['AuthorityLevel'];
	}

	//display the page for adding a new record to the table
	public function add ($post)
	{
		//error messages
		$msgs = array();

		//main message
		$msg  = "";

		//if data has been submitted, prefill
		if ($this->submitted($post))
		{
			//initialize error message array
			$msgs = $this->fields;

			//copy POST data
			$this->fields = $post;
			unset ($this->fields['action']);
			unset ($this->fields['selected']);

			//validate input in add mode
			if ($this->validate ($msgs, false))
			{
				//update database
				$this->insert();

				//confirmation message
				$msg = $this->confirm_add();

				//clear fields for next entry
				$this->fields = array_fill_keys (array_keys($this->fields), "");

			} //end if valid input

			//if invalid input
			else
			{
				//color error messages red
				foreach ($msgs as &$error)
				{
					if ($error != "")
						$error = '<font color="red">' . $error . '</font><br>';
				}

				//set main error message
				$msg =
					'<font color="red">' .
						'Some fields require attention.' .
					'</font><br>'
				;

				unset ($error);

			} //end if invalid input

		} //end if data submitted

		//get necessary options from database
		$options = $this->get_options();

		//print form
		$action = "add";
		include $this->view . ".php";

		//I don't know if this will work correctly, but we'll see
		exit();

		return "";

	} //end function add()

	//display the page for viewing and editing an existing record of the table
	public function edit ($post)
	{
		//error message
		$msg = "";

		//array of selected records
		$selected = array();
		if (isset ($post['selected']))
			$selected = $post['selected'];

		//count == 0
		if (count($selected) === 0)
			$msg = "Please select a " . $this->view . " to view/edit.";

		//count == 1
		elseif (count($selected) === 1)
		{
			//error messages
			$msgs = array();

			//main message
			$msg  = "";

			//if data has been submitted, prefill
			if ($this->submitted($post))
			{
				//initialize error message array
				$msgs = array_fill_keys(array_keys($this->fields), "");

				//copy POST data
				$this->fields = $post;
				unset ($this->fields['action']);
				unset ($this->fields['selected']);

				//validate input in edit mode
				if ($this->validate ($msgs, $post['selected'][0]))
				{
					//append ID to admin
					$this->fields["ID"] = $post['selected'][0];

					//update database
					$this->update();

					//confirmation message
					$msg = $this->confirm_edit();

				} //end if valid input

				//if invalid input
				else
				{
					//main error message
					$msg =
						'<font color="red">' .
							'Some fields require attention.' .
						'</font><br>'
					;

					//color error messages red
					foreach ($msgs as &$error)
						$error = '<font color="red">' . $error . '</font><br>';

					unset ($error);

				} //end if invalid input

			} //end if data submitted

			//prefill fields from database if first edit attempt
			else $this->prefill ($selected[0]);

			//get necessary options from database
			$options = $this->get_options();

			//display form
			$action = "edit";
			include $this->view . ".php";

			exit();

			return "";
		} //end count === 1

		//count > 1
		else
			$msg = "Please select only one " . $this->edit . " to view/edit.";

		return '<font color="red">' . $msg . '</font>';

	} //end function edit()

	//delete the selected record(s)
	public function delete ($post)
	{
		//error or confirmation message
		$msg = "";

		//array of selected records
		$selected = array();
		if (isset ($post['selected']))
			$selected = $post['selected'];

			//at least 1 record selected
		if (count($selected) > 0)
		{
			//how many records were actually deleted
			$deleted = 0;

			//which records were not deleted
			$not_deleted = array();

			//delete
			foreach ($selected as $id)
			{
				if ($this->connection->exec
				("
					delete from " . $this->table . "
					where " . $this->table . "ID = " . $id
				))
				{
					$deleted ++;
				}
				else
					array_push ($not_deleted, $this->connection->errorInfo()[2]); 

			} //end deletion

			//remove empty elements from $not_deleted
			$not_deleted = array_diff($not_deleted, array(""));

			//get feedback from database
			$msg = $deleted . " ";

			if ($deleted === 1)
				$msg .= $this->view;
			else
				$msg .= strtolower($this->title);

			$msg = '<font color="green">' . $msg .  " deleted.</font><br>";

			//compose the rest of the error message
			//e.g. "Marion County could not be deleted because there is at least one school that depends on it.<br>"
			if (isset($not_deleted[0]))
			{
				$msg .= '<font color="red">' . $not_deleted[0];

				//if more than 1 not deleted, use 'and'
				if (count($not_deleted) > 1)
				{
					//if more than 2 not deleted, use comma(s)
					if (count($not_deleted) > 2)
					{
						$msg .= ", ";

						for ($i = 1; $i < count($not_deleted) - 1; $i++)
							$msg .= $not_deleted[$i] .	", ";
					}
					else
						$msg .= " ";

					$msg .= "and " . end($not_deleted);

				} //end more than 1 not deleted

				$msg .= " could not be deleted because there is at least one " .
				$this->dependent . " that depends on ";

				if (count($not_deleted) == 1)
					$msg .= "it.";
				else
					$msg .= "them.";

				$msg .= "</font><br>";

			} //end not deleted

			//return the error or confirmation message
			return $msg;

		} //end at least 1 record selected

		//no records selected
		else
		{
			$msg = "Please select " . strtolower($this->title) . " to delete.";
			return '<font color=red>' . $msg . '</font>';
		}

	} //end function delete()

	//check whether data has been submitted
	abstract protected function submitted ($post);

	/*
		validate field entries and update msgs array

		When validating an edit, $original should be the ID of the record being
		edited, and the boolean value false otherwise.
	*/
	abstract protected function validate (&$msgs, $original);

	//return an array of option arrays for the form to use
	abstract protected function get_options();

	//insert data from fields array into database
	abstract protected function insert();

	//update database with data from fields array
	abstract protected function update();

	//confirm an add operation
	abstract protected function confirm_add();

	//confirm an edit operation
	abstract protected function confirm_edit();

	//return an array of fields and values of the target record from the database
	abstract protected function prefill ($target);

} //end class Entity

?>
