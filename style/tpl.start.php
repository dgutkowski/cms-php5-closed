<?php

function check_current($item,$actual)
{
	if($item == $actual) return " class='current-page'";
}

echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>\n";
echo "<html xmlns='http://www.w3.org/1999/xhtml' lang='pl' xml:lang='pl'>\n";
echo "<head>\n";
echo "  <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-2' />\n";
echo "  <meta name='description' content='".$config['desc']."' />\n";
echo "  <meta name='keywords' content='".$config['keys']."' />\n";
echo "  <meta name='author' content='".$config['author']."' />\n";
echo "  <meta name='robots' content='index, nofollow' />\n";
echo "  <title>".$config['title']."</title>\n";
echo "  <link rel='stylesheet' href='style/style.css' type='text/css' />\n";
echo "  <link rel='stylesheet' href='style/style.lea.css' type='text/css' />\n";
echo "  <link rel='shortcut icon' href='".$config['fav_ico']."' />\n";
echo "</head>\n";
echo "<body>\n";
echo "\n";
echo "<div id='header-super'>\n";
echo "  <div class='wrap-center'>\n";
echo "    <div id='title'>\n";
echo "      <h1>".$config['header_text_main']."</h1>\n";
echo "      <p class='textsub'>".$config['header_text_sub']."</p>\n";
echo "    </div>\n";
echo "  </div>\n";
echo "</div>\n";
echo "\n";
echo "<div id='header-sub'>\n";
echo "  <div class='wrap-center'>\n";
echo "    <div id='navigation-horizontal'>\n";
echo "      <ul>\n";
echo "      <li".check_current($config['mainpage'],@$actual)."><a href='index.php'>Strona glówna</a></li>\n";
echo "      <li".check_current("contact.php",@$actual)."><a href='contact.php'>Kontakt</a></li>\n";
echo "      <li".check_current("faq.php",@$actual)."><a href='faq.php'>FAQ</a></li>\n";
echo "      <li".check_current("links.php",@$actual)."><a href='links.php'>Linki</a></li>\n";
echo "      <li".check_current("download.php",@$actual)."><a href='download.php'>Download</a></li>\n";
echo "      <li".check_current("forum.php",@$actual)."><a href='";
							if($config['forum_sys'] == 1) echo "forum.php"; 
							else echo $config['forum_link']; echo "'>Forum</a></li>\n";
echo "      </ul>\n";
echo "    </div>\n";
echo "  </div>\n";
echo "  <div id='line'></div>\n";
echo "</div>\n";
echo "\n";
echo "<div id='content-area'>\n";
echo "  <div class='wrap-center'>\n";
if(!isset($wide_screen) OR $wide_screen == 0)
{
	echo "    <div id='side-bar'>\n";
	require_once("tpl.side.php");
	echo "    <br>\n";
	echo "    </div>\n";
	echo "    <div id='main-bar'>\n";
}
else
{
	echo "    <div id='wide-bar'>\n";
}
echo "\n";
echo "    <!-- ~~~~~~~~~~~~~~~~~~~~ -->\n";
echo "\n";
?>
