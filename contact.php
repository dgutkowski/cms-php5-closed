<?php
require_once("maincore.php");
$actual = "contact.php";
add_title("Kontakt");
require_once(BASEDIR.STYLE."tpl.start.php");

echo "<h2>Kontakt</h2>\n";

$sql = "SELECT * FROM ".DBPREFIX."users WHERE `active`='1' AND `level` > '100'";
	$query = mysql_query($sql);
	if(@mysql_num_rows($query)>0)
	{
   	while($user = mysql_fetch_assoc($query))
    	{
      	echo "  <h5>".$user['login']."</h5>\n";
	   	echo "    <ul>\n";
	   	echo "    <li>e-Mail: <a href='mailto:".$user['mail']."'>".$user['mail']."</a></li>\n";
	   	if( $user['gg'] != 0 AND !empty($user['gg']) ) echo "    <li>GG: <a href='gg:".$user['gg']."'>".$user['gg']."</a></li>\n";
	   	if( $user['icq'] != 0 AND !empty($user['icq']) ) echo "    <li>ICQ: <a href='icq:".$user['icq']."'>".$user['icq']."</a></li>\n";
			echo "    </ul><br/>\n";
		}
	}
	
	
require_once(BASEDIR.STYLE."tpl.end.php");
?>
