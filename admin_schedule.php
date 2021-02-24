<?php 
    include "header.php";
    $sql = "SELECT ProjectID FROM Project";
    $column_num = $connection->prepare($sql);
    $column_num->execute();
    $counter = 0;
    $result =array();
    while($result = $column_num->fetch(PDO::FETCH_ASSOC)){
        $counter++;
    };
    $max_num = $counter;
    var_dump($counter);
?> 



   <div class="wrapper">
<div class="main-f">
    <h1>Schedule</h1> 
<div class="table">
    <table border="5" cellspacing="0" align="center" class="the-table"> 
              <tr> 
            <td align="center" height="50" 
                 ><br> 
                <b>TIME</b></br> 
            </td>
<?php
	for($x = 1;$x<=$max_num;$x++){
            print "<td> <b>Project $x</b></td>";

               
}
           ?>
        </tr> 

        <tr> 
            
            <td align="center" height="50"  >
                <b><br>9:00-9:30</b></td>
<?php
	for($x = 1;$x<=$max_num;$x++){
            print "<td>Booth 1</td> ";
               }
?>
        </tr> 

        <tr> 
            <td align="center" height="50"  >
                        <b><br>9:30-10:00</b></td>
<?php
	for($x = 1;$x<=$max_num;$x++){
            print "<td>Booth 3</td> ";
               }
?>

        </tr> 

        <tr> 
           
            <td align="center" height="50"  >
                        <b><br>10:00-10:30</b></td>
<?php
	for($x = 1;$x<=$max_num;$x++){
            print "<td>Booth 3</td> ";
               }
?>

        </tr>
 
        <tr> 
                <td align="center" height="50"  >
                    <b>10:30-11:00</b>
                </td>
 <?php
	for($x = 1;$x<=$max_num;$x++){
            print "<td>Booth 3</td> ";
               }
?>
        </tr>
 
        <tr> 
            <td align="center" height="50"  >
                <b><br>11:00-12:00</b>
            </td>
<?php
	for($x = 1;$x<=$max_num;$x++){
            print "<td>Lunch</td> ";
               }
?>

        </tr>

        <tr> 

            <td align="center" height="50"  >
                <b><br>12:00-12:30</b>
            </td>
<?php
	for($x = 1;$x<=$max_num;$x++){
            print "<td>Booth 3</td> ";
               }
?>
        </tr> 

        <tr>

        <td align="center" height="50"  >
                <b><br>12:30-1:00</b>
 	</td>

<?php
	for($x = 1;$x<=$max_num;$x++){
            print "<td>Booth 3</td> ";
               }
?>
	</tr>

        <tr>
                        
	 <td align="center" height="50"  >
                   <b><br>1:30-2:00</b> </td>
<?php
	for($x = 1;$x<=$max_num;$x++){
            print "<td>Booth 3</td> ";
               }
?>

        </tr>

         <tr>
                        
         <td align="center" height="50"  >
                <b><br>2:00-2:30</b></td>
<?php
	for($x = 1;$x<=$max_num;$x++){
            print "<td>Booth 3</td> ";
               }
?>

	 </tr>

         <tr>
                                            
          <td align="center" height="50"  >
            <b><br>2:30-3:00</b>
            </td>
<?php
	for($x = 1;$x<=$max_num;$x++){
            print "<td>Booth 3</td> ";
               }
?>
                                            
            </tr>
    </table> 
</div>
    </div>
    </div>
