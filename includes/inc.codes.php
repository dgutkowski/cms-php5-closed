<?php
function emots($tekst)
{
	$sql = "SELECT * FROM `".DBPREFIX."reg_emots` WHERE `display` = '1'";
  $query = mysql_query($sql);
  if(mysql_num_rows($query)>0)
  {
    while($emot = mysql_fetch_assoc($query))
    {
      $tekst = str_replace($emot['code'],"<img src='".IMAGES."emots/".$emot['image']."' width='14' height='14' border='0'/>",$tekst);
    }
  }
  return $tekst;
}
function codes($tekst)
{  
  $tekst = stripslashes($tekst);
  $tekst = htmlspecialchars($tekst);
  $tekst = nl2br($tekst);
  
  $tekst = preg_replace("#\[b\](.*?)\[/b\]#si",'<b>\\1</b>',$tekst);
  $tekst = preg_replace("#\[i\](.*?)\[/i\]#si",'<i>\\1</i>',$tekst);  
  $tekst = preg_replace("#\[u\](.*?)\[/u\]#si",'<u>\\1</u>',$tekst);
  $tekst = preg_replace("#\[s\](.*?)\[/s\]#si",'<s>\\1</s>',$tekst);
  $tekst = preg_replace("#\[size=small\](.*?)\[/size\]#si",'<span class=\'text-tiny\'>\\1</span>',$tekst);
  $tekst = preg_replace("#\[url\](.*?)\[/url\]#si", "<a href=\"\\1\">\\1</a>", $tekst);
  $tekst = preg_replace("#\[url=(.*?)\](.*?)\[/url\]#si", "<a href=\"\\1\">\\2</a>", $tekst);
  $tekst = preg_replace("#\[img\](.*?)\[/img\]#si",'<img src="\\1"/>',$tekst);
  $tekst = preg_replace("#\[code\](.*?)\[/code\]#si",'<pre>\\1</pre>',$tekst);
  
  $tekst = preg_replace("#\[ul\](.*?)\[/ul\]#si",'<ul>\\1</ul>',$tekst);
  $tekst = preg_replace("#\[li\](.*?)\[/li\]#si",'<li>\\1</li>',$tekst);
  $tekst = preg_replace("#\[clan\](.*?)\[/clan\]#si","<img src='images/clans/\\1.gif' border='0'/>",$tekst);
  
  $tekst = emots($tekst);
  
  $sql = "SELECT * FROM `".DBPREFIX."reg_countries`";
  $query = mysql_query($sql);
  if(mysql_num_rows($query)>0)
  {
    while($lang = mysql_fetch_assoc($query))
    {
      $tekst = str_replace("[lang]".$lang['code']."[/lang]","<a name='".$lang['code']."'></a><h4 class='lang'>&nbsp;&nbsp;<img src='".IMAGES."flags/small_".$lang['code'].".png' border='0'/> ".$lang['lang']."</h4>",$tekst);
    }
  }
  return $tekst;
}
?>
