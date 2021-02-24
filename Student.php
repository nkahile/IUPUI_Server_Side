<!--

	Student.php

	Student is a class that inherits from Entity, overriding abstract methods to
	achieve polymorphic behavior.

-->

<?php

include "Entity.php";

class Student extends Entity
{
	//constructor
	function __construct ($connection)
	{
		//initialize $table, $title, $view, and $connection
		parent::__construct ($connection);

		//empty fields
		$this->fields = array
		(
			"SchoolID"   => "",
			"FirstName"  => "",
			"MiddleName" => "",
			"LastName"   => "",
			"Gender"     => "",
			"ProjectID"  => "",
			"GradeID"    => ""
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
				StudentID as ID,
				CONCAT (
					LEFT (LastName,  15), ', ',
					LEFT (FirstName, 15), ' ',
					LEFT (MiddleName, 1), '., (',
					LEFT (SchoolName, 15), ')'
				) as selection
				from Student, School
				where Student.SchoolID = School.SchoolID
				order by LastName
		");
		$records = $record_set->fetchAll();
		$record_set->closeCursor();

		//return records
		return $records;

	} //end function display_data();

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

		return $valid;
	
	} //end function validate()

	//get options from database
	protected function get_options()
	{
		$options = array();

		//School
		$record_set = $this->connection->query
			("select SchoolID, SchoolName from School");
		$options['schools'] = $record_set->fetchAll();

		//Project
		$record_set = $this->connection->query ("select ProjectID, Title from Project");
		$options['projects'] = $record_set->fetchAll();

		//Grade
		$record_set = $this->connection->query ("select GradeID, GradeNum from Grade");
		$options['grades'] = $record_set->fetchAll();

		return $options;

	} //end function get_options()

	//insert data from fields array into database
	protected function insert()
	{
		$query = $this->connection->prepare
		("
			insert into
				Student (SchoolID, FirstName, MiddleName, LastName, Gender, ProjectID, GradeID)
				values  (        ?,        ?,          ?,        ?,      ?,         ?,       ?)
		");

		$query->execute (array_values($this->fields));

	} //end function insert()

	//update database with data from fields array
	protected function update()
	{
		$query = $this->connection->prepare
		("
			update Student
			set
				SchoolID = ?,
				FirstName = ?,
				MiddleName = ?,
				LastName = ?,
				Gender = ?,
				ProjectID = ?,
				GradeID = ?
			where StudentID = ?
		");

		$query->execute (array_values($this->fields));

	} //end function update()

	//confirm an add operation
	protected function confirm_add()
	{
		$msg =
			$this->fields['FirstName'] .
			$this->fields['LastName'] .
			' added as student.'
		;
		
		return '<font color="green">' . $msg . '</font><br>';
	
	} //end function confirm_add()

	//confirm an edit operation
	protected function confirm_edit ()
	{
		$msg =
			$this->fields['FirstName'] . ' ' .
			$this->fields['LastName'] .
			' successfully modified.'
		;
		
		return '<font color="green">' . $msg . '</font><br>';

	} //end function confirm_edit()

	//return an array of fields and values of the target record from the database
	protected function prefill ($target)
	{
		$record_set = $this->connection->query
		("
			select
				SchoolID, FirstName, MiddleName, LastName,
				Gender, ProjectID, GradeID
			from Student
			where StudentID = " . $target
		);

		$this->fields = $record_set->fetch (PDO::FETCH_ASSOC);
	
	} //end function prefill()

} //end class Student
