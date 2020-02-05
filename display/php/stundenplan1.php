





$result = $cmsDB->query("SELECT * FROM ".$cmsDB->prefix("departments")."");

echo "<br /><br /><table class='table table-bordered table-striped'>
<tr>
<th>Bil</th>
<th>Department</th>
<th>Staff</th>
</tr>";

$count =1 ;

while($row = $cmsDB->fetchArray($result))
{
$deptid=$row['deptid']; 
$deptname=$row['deptname']; 

echo "<tr><td>".$count++."</td><td>".$deptname."</td><td>";
global $cmsDB;
  $result2 = $cmsDB->query("SELECT * FROM ".$cmsDB->prefix("staff")." WHERE deptid=$deptid");

while($row = $cmsDB->fetchArray($result2))
{

$name=$row['name']; 
echo "".$name." <br />";

}

}


echo "</td></tr></table>";