<?php
require_once("maincore.php");
$actual = "faq.php";
add_title("FAQ");
require_once(BASEDIR.STYLE."tpl.start.php");

echo "<h2>FAQ</h2>\n";

	$sql = "SELECT * FROM ".DBPREFIX."faq ORDER BY `order`";		
	$query = mysql_query($sql);
	if(mysql_num_rows($query) > 0)
	{
	    while($faq = mysql_fetch_assoc($query))
	    {
			echo "<div class='faq-question'><b>".stripslashes($faq['question'])."</b></div>\n";
			echo "<div class='faq-answer'>".stripslashes($faq['answer'])."</div>\n";
		}
		
	}

require_once(BASEDIR.STYLE."tpl.end.php");
?>
