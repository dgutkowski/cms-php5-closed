<style type="text/css">
table {
   border:1px solid #D00;
}
th {
   background:#000;color:#FFF;
}

td {
   width:22px;text-align:center;
}

.sunday { 
   color:#d00;font-weight:bold;
} 
.saturday { 
   font-weight:bold;
} 
</style>

<?php
$CountOfDays = array(31,28,31,30,31,30,31,31,30,31,30,31);
if((date('Y')%4) == 0) $CountOfDays[1] = 29;
$m['actual'] = date('n')-1;
$m['name'] = array('Styczeñ','Luty','Marzec','Kwiecieñ','Maj','Czerwiec','Lipiec','Sierpieñ','Wrzesieñ','Pa¼dziernik','Listopad','Grudzieñ');
$d['actual']['week'] = date('w');
$d['actual']['month'] = date('j');
$d['first']['week'] = date('w');
$test = date('j');
while( $test > 7 )
{ $test = $test - 7; }
while( $test != 1 )
{ $d['first']['week'] = $d['first']['week'] - 1;$test = $test-1; if($d['first']['week'] == -1) $d['first']['week'] = 6; }
echo "<table>";
echo "<tr><th colspan='7'>".$m['name'][$m['actual']]."</th></tr>";
echo "<tr><td class='sunday'>N</td><td>P</td><td>W</td><td>¦</td><td>C</td><td>P</td><td class='saturday'>S</td></tr>";
echo "<tr>";
for( $i = 0;$i < $d['first']['week'];$i++){ echo "<td>0</td>"; }
$d['printed']['week'] = $d['first']['week'];
for( $i=1;$i<=$CountOfDays[$m['actual']];$i++ )
{
	if( $d['printed']['week'] == 7 ){ $d['printed']['week'] = 0;echo "</tr><tr>"; }
	echo "<td>".$i."</td>";
	$d['printed']['week'] = $d['printed']['week']+1;
}
echo "</tr>";
echo "</table>";
?>
