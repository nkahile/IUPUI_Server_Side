<?php include "connect.php" ?>
<!--
	generate.php

	Included by Schedule.php via AJAX when the admin wants to automatically
	generate a new schedule.

	Database connection is already established.
-->

<?php

	//because who need efficiency?
	ini_set('memory_limit', '64M');
	set_time_limit (100000);

	$records = $connection->query ("select ProjectID from Projects");

	$project_schedules = array_values (projects (array_keys ($projects), $timeslots));
	
	$schedules = schedules (array_keys ($judges), $project_schedules);

	foreach ($schedules as $schedule)
	{
		foreach ($schedule as $judge => $project_schedule)
		{
			print ('<p>___' . $judge. '</p>');

			foreach ($project_schedule as $timeslot => $project)
				print ('<p>___' . $timeslot . ' => ' . $project . '</p>');
		}

		$utility = utility ($schedule, $projects, $judges);

		print ('<br>');

	} //end printing

	//initialize schedules array and call build_schedules
	function schedules ($judges, $project_schedules)
	{
		$schedule = array();
		$project_schedule = reset ($project_schedules);

		foreach ($judges as $judge)
			$schedule [$judge] = $project_schedule;

		$schedules = array ($schedule);

		return build_schedules ($judges, $project_schedules, $schedules);

	} //end def schedules()

	//return all possible schedules for judges for the day
	function build_schedules ($judges, $project_schedules, &$schedules)
	{
		print ('in schedules');
		//base case
		if (count($judges) === 0)
		{
			print (' base case');
			return $schedules;
		}

		//general case
		foreach ($project_schedules as $project_schedule)
		{
			$schedules = build_schedules (
				array_values (array_diff ($judges, array ($judges[0]))), //judges
				$project_schedules,
				$schedules
			);

			print(' after base case');

			print ('$schedules[0] set; copying');
			$schedule = end ($schedules);

			print('assigned $schedule [' . $judges[0] . ']</p>');
			$schedule [$judges[0]] = $project_schedule;

			foreach ($schedule as $judge => $project_schedule)
			{
				print('<p>__' . $judge . '</p>');
				foreach ($project_schedule as $timeslot => $project)
					print ('<p>___' . $timeslot . ' => ' . $project . '</p>');;
			}

			array_push ($schedules, $schedule);
		}

		return $schedules;
	}

	//initialize project schedule and call build_projects()
	function projects (
			$projects,
			$timeslots
		)
	{ 
		$schedule = array();
		//map timeslots to projects
		for ($i = 0; $i < count ($timeslots); $i++)
			$schedule [$timeslots[$i]] = $projects[$i];

		$schedules = array($schedule);
		
		//build project schedule from initialized version
		return build_projects ($projects, $timeslots, $schedules);

	} //end def projects()

	//return all project schedules that a judge could possible have for a day
	//recursion go brrrr
	function build_projects (
			$projects,  //array of projects not yet judged by the judge
			$timeslots, //array of timeslots the judge hasn't used yet
			&$schedules //holds associative arrays $timeslot => $project
		)
	{
		//base case
		//the judge has no timeslots left
		if (count($timeslots) === 0)
		{
			return;
		}

		//recursive case
		//the judge has one or more unassigned timeslot
		//for each project
		$unjudged = count($projects) - count($timeslots);
		for ($i = 0; $i <= $unjudged; $i++)
		{
			//generate all possible schedules for each project the next timeslot
			//could be assigned to

			//copy the latest potential schedule
			if (isset ($schedules[0]))
				$schedule = end($schedules);
			
			//assign the next project to the last timeslot
			$schedule [$timeslots[0]] = $projects[0];
			array_push ($schedules, $schedule);

			//never assign this project again
			$projects =
				array_values (array_diff ($projects,  array($projects[0])));

			//get all possible schedules for the remaining timeslots
			build_projects (
				//projects remaining to be assigned
				$projects,

				//unfilled timeslots
				array_values (array_diff ($timeslots, array($timeslots[0]))),

				//list to append potential schedules to
				$schedules
			);
		} //end for each project

		return $schedules;

	} //end function choose_projects()

	function utility ($schedule, $judges, $projects)
	{
		//the number of visits each project gets
		$project_visits = array();

		//how well the categories match judges' preferences
		$category_match = 0;

		//how many grades away from judges' grade preference projects are
		$grade_distance = 0;

		//for each judge
		foreach ($schedule as $judge_id => $project_schedule)
		{
			//for each visit
			foreach ($project_schedule as $timeslot => $project_id)
			{
				if (!isset ($project_visits [$project_id]))
					$project_visits [$project_id] = 0;

				//count the visit
				$project_visits [$project_id] += 1;

				//category matching
				//if project matches first category preference, award 3 points
				if ($judges[$judge_id]['CatPref1'] == $projects[$project_id]['Category'])
					$category_match += 3;

				//if project matches second category preference, award 2 points
				elseif ($judges[$judge_id]['CatPref2'] == $projects[$project_id]['Category'])
					$category_match += 2;

				//if project matches third category preference, award 1 point
				elseif ($judges[$judge_id]['CatPref3'] == $projects[$project_id]['Category'])
					$category_match += 1;

				//grade matching: distance between grade level and pref endpoint
				if ($projects[$project_id]['GradeID'] < $judges[$judge_id]['LowerGradePref'])
					$grade_distance += $judges[$judge_id]['LowerGradePref'] - $projects[$project_id]['GradeID'];

				elseif ($projects[$project_id]['GradeID'] > $judges[$judge_id]['UpperGradePref'])
					$grade_distance += $projects[$project_id]['GradeID'] - $judges[$judge_id]['UpperGradePref'];

			} //end for each visit

		} //end for each judge

		print('<p>min_project_visits: ' . min($project_visits) . '</p>');
		print('<p>category_match: ' . $category_match . '</p>');
		print('<p>grade_distance: ' . $grade_distance . '</p>');
		print('<p>total utility: ' . min($project_visits) + $category_match - $grade_distance);

		//calculate schedule utility
		return min($project_visits) + $category_match - $grade_distance;

	} //end def utility()
