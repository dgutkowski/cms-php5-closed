<?php
require_once "maincore.php";
$actual = "download.php";
add_title("Download");
require_once (BASEDIR.STYLE."tpl.start.php");

echo "<h2>Download</h2>\n";

if( check_int($_GET['id']) == TRUE )
{
	$sql = "SELECT `name`, `url`, `direct` FROM ".DBPREFIX."files WHERE `id` = '".$_GET['id']."' LIMIT 1";		
	$query = mysql_query($sql);
	if(mysql_num_rows($query) > 0)
	{
		$file = mysql_fetch_assoc($query);
		if(file_exists("files/".$file['url'])) redirect("files/".$file['url']);
		else
		{
			echo "<div class='err'>";
			echo "Brak danego pliku.";
			echo "</div>\n";
		}
	}
}

require_once (BASEDIR.STYLE."tpl.end.php");
?>
