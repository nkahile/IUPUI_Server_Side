<?php

	print($_SESSION['Judge']);

	$records = $connection->query
	("
		select time(StartTime) as Start, time(EndTime) as End, ProjectNum, Title, BoothNum
		from Schedule, Session, Project, Booth
		where
			Schedule.ProjectID = Project.ProjectID and
			Project.BoothID    = Booth.BoothID     and
			Schedule.SessionID = Session.SessionID and
			Schedule.JudgeID   = " . $_SESSION['Judge']
	);
	$visits = $records->fetchAll (PDO::FETCH_ASSOC);

?>

   <div class="wrapper">
<div class="main-f">
    <h1>Schedule</h1> 

    <table border="5" cellspacing="0" align="center" class="the-table"> 
              <tr> 
            <td align="center" height="50" 
                width="100"><br> 
                <b>TIME</b></br> 
            </td>

            <td align="center" height="50">
                <b>Date</b></td>
				</tr>
		<?php

			foreach ($visits as $visit)
			{
				print
				('
        <tr> 
            
            <td align="center" height="50" width="400">
                <b><br>' .
				$visit['Start'] . ' - ' .
				$visit['End'] . '</b></td>

            <td align="center"  height="50" width="400"> Project ' .
				$visit['ProjectNum'] . ': "' .
				$visit['Title'] . '" at Booth ' .
				$visit['BoothNum'] . '</td></tr>');
			}
		?>

    </table> 
    </div>
    </div>
