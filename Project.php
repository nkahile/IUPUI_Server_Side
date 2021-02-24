<!--

	Project.php

	Project is a class that inherits from Entity, overriding abstract methods
	to achieve polymorphic behavior.

-->

<?php

include "Entity.php";

class Project extends Entity
{
	//constructor
	function __construct ($connection)
	{
		//initialize $table, $title, $view, and $connection
		parent::__construct ($connection);

		//empty fields
		$this->fields = array
		(
			"BoothID"    => "",
			"CategoryID" => "",
			"ProjectNum" => "",
			"Title"      => "",
			"Abstract"   => ""
		);

	} //end constructor

	/* Override abstract methods */
	//select identifying data from records
	public function display_data()
	{
		//get records
		$record_set = $this->connection->query
		("
			select ProjectID as ID,
			Title as selection
			from Project
		");
		$records = $record_set->fetchAll();
		$record_set->closeCursor();

		//return records
		return $records;

	} //end function display_data();
	
	//check whether data has been submitted
	protected function submitted ($post)
	{
		return isset ($post["Title"]);
	}

	//validate field entries and update msgs array
	protected function validate (&$msgs, $original)
	{
		//set empty original to NULL if adding
		if ($original == false)
			$original = "NULL";

		//assume input valid
		$valid = true;

		//title validity
		if ($this->fields['Title'] == "")
		{
			$valid = false;
			$msgs['Title'] = "Project Title cannot be empty.";
		}

		//title uniqueness
		else
		{
			$query = $this->connection->prepare
			("
				select count(*) as 'count'
				from Project
				where
					Title = ? AND
					Year  = YEAR(CURDATE()) AND
					NOT ProjectID <=> " . $original //NULL-safe equals operator
			);
			$query->execute(array($this->fields['Title']));
			$count = $query->fetch(PDO::FETCH_ASSOC)['count'];

			if ($count !== "0")
			{
				$valid = false;
				$msgs['Title'] =
					"There is already a project with that title this year.";
			}
		} //end title uniqueness check

		//Project Number
		if ($this->fields['ProjectNum'] != "")
		{
			$query = $this->connection->prepare
			("
				select count(*) as 'count'
				from Project
				where
					ProjectNum = ? AND
					Year  = YEAR(CURDATE()) AND
					NOT ProjectID <=> " . $original //NULL-safe equals operator
			);
			$query->execute(array($this->fields['ProjectNum']));
			$count = $query->fetch(PDO::FETCH_ASSOC)['count'];

			if ($count !== "0")
			{
				$valid = false;
				$msgs['ProjectNum'] = "There is already a project with this number this year.";
			}
		} //end Project Number uniqueness check

		return $valid;

	} //end function validate()

	//return an array of option arrays for the form to use
	protected function get_options()
	{
		$options = array();

		//BoothNums
		$record_set = $this->connection->query ("select BoothID, BoothNum from Booth");
		$options['booths'] = $record_set->fetchAll();

		//Categories
		$record_set = $this->connection->query
			("select CategoryID, CategoryName from Category");
		$options['categories'] = $record_set->fetchAll();

		return $options;

	} //end function get_options()

	//insert data from fields array into database
	protected function insert()
	{
		$this->autofill_ProjectNum();

		foreach ($this->fields as $field)
			print ($field . "/<br>");

		$query = $this->connection->prepare
		("
			insert into
				Project (BoothID, CategoryID, ProjectNum, Title, Abstract)
				values  (      ?,        ?,          ?,     ?,        ?)
		");

		$query->execute (array_values($this->fields));

	} //end function insert()

	//update database with data from fields array
	protected function update()
	{
		$this->autofill_ProjectNum();

		$query = $this->connection->prepare(
		"
			update Project
			set
				BoothID    = ?,
				CategoryID = ?,
				ProjectNum = ?,
				Title      = ?,
				Abstract   = ?
			where ProjectID = ?
		");

		$query->execute (array_values($this->fields));

	} //end function update()

	//autofill ProjectNum, used by insert() and update()
	private function autofill_ProjectNum()
	{
		if ($this->fields['ProjectNum'] == "")
		{
			$record_set = $this->connection->query
			("
				select MAX(ProjectNum) + 1 as NewNum
				from Project
				where Year = YEAR(CURDATE())
			");

			$this->fields['ProjectNum'] =
				$record_set->fetch(PDO::FETCH_ASSOC)['NewNum'];
		}
	} //end function autofill_ProjectNum()

	//confirm an add operation
	protected function confirm_add()
	{
		$msg = 'Successfully added project ' . $this->fields['Title'];
		return '<font color="green">' . $msg . '</font><br>';

	} //end function confirm_add()

	//confirm an edit operation
	protected function confirm_edit()
	{
		$msg = 'Successfully modified project ' . $this->fields['Title'];
		return '<font color="green">' . $msg . '</font><br>';

	} //end function confirm_edit()

	//set fields array to columns and values of target record from database
	protected function prefill ($target)
	{
		$record_set = $this->connection->query
		("
			select BoothID, CategoryID, ProjectNum, Title, Abstract
			from Project
			where ProjectID = " . $target
		);
		$this->fields = $record_set->fetch (PDO::FETCH_ASSOC);

	} //end function prefill()

} //end class Project
