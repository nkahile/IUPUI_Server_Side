<?php 
    include "header.php";
    $sql = "SELECT Title FROM Project";
    $name = $connection->prepare($sql);
    $name->execute();
    $rows = array();
    while($row = $name->fetch(PDO::FETCH_ASSOC)){
        $rows[] = $row;
    };
   

?> 

   <div class="wrapper">
<div class="main-f">
    <select class="student-schedule">
        <option value=""><?php echo $rows[0]['Title'];?></option>
        <option value=""><?php echo $rows[1]['Title'];?></option>


    </select>
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

        <tr> 
            
            <td align="center" height="50" width="100">
                <b><br>9:00-9:30</b></td>
            <td align="center"  height="50">Judge 1</td> 
        
        </tr> 

        <tr> 
            <td align="center" height="50" width="100">
                        <b><br>9:30-10:00</b>
            </td>
            <td align="center" height="50">Judge 2</td> 

        </tr> 

        <tr> 
           
            <td align="center" height="50" width="100">
                        <b><br>10:00-10:30</b>
            </td>
            <td align="center"height="50">Judge 3</td>
            </td> 

        </tr>
 
        <tr> 
                <td align="center" height="50" width="100">
                    <b>10:30-11:00</b>
                </td>
            <td align="center" height="50">Judge 4</td>
        </tr>
 
        <tr> 
            <td align="center" height="50" width="100">
                <b><br>11:00-12:00</b>
            </td>
            <td align="center"height="50">Lunch</td>

        </tr>

        <tr> 

            <td align="center" height="50" width="100">
                <b><br>12:00-12:30</b>
            </td>
            <td align="center" height="50">Judge 5</td>
        </tr> 

        <tr>

        <td align="center" height="50" width="100">
                <b><br>12:30-1:00</b>
 	</td>

        	<td align="center" height="50">Judge 6</td>
	</tr>

        <tr>
                        
	 <td align="center" height="50" width="100">
                   <b><br>1:30-2:00</b> </td>
          <td align="center"  height="50">Break</td>

        </tr>

         <tr>
                        
         <td align="center" height="50" width="100">
                <b><br>2:00-2:30</b></td>
        <td align="center" width="400" height="50">Judge 7</td>

	 </tr>

         <tr>
                                            
          <td align="center" height="50" width="100">
            <b><br>2:30-3:00</b>
            </td>
             <td align="center" width="400" height="50">Judge 8</td>
                                            
            </tr>
    </table> 
    </div>
    </div>
