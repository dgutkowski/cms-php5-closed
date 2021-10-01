<?php

/*
/* Maincore file
/* Created by Daniel Gutkowski
/* for Polish Revolution clan
/* (c) 2012 All rights reserved
/* Integral part of Polish Revolution's website
/*----------------------------------------------*/

ob_start();
if (preg_match("/maincore.php/i", $_SERVER['PHP_SELF'])) { die(""); }

$level = ""; $i = 0;
while (!file_exists($level."config.php")) {
	$level .= "../"; $i++;
	if ($i == 5) { die("Config file not found"); }
}

require_once $level."config.php";
if (!isset($dbname)) redirect("install.php");

define("BASEDIR", $level);

define("USER_IP", $_SERVER['REMOTE_ADDR']);
define("SCRIPT_SELF", basename($_SERVER['PHP_SELF']));
define("ADMIN", BASEDIR."administration/");
define("IMAGES", BASEDIR."images/");
define("INCLUDES", BASEDIR."includes/");
define("STYLE", "style/");

define("DATE_SET", date("Y-m-d"));
define("DATETIME", date("Y-m-d H:i:s"));
define("TIMESTAMP", time());

define("SPECIAL_LVL", 101);

$dbconnect = @mysql_connect($dbhost, $dbuser, $dbpass);
$dbselect = @mysql_select_db($dbname);

if(!$dbconnect) 
	die("<div style='font-size:14px;font-weight:bold;align:center;'>Nie nawi±zano po³±czenia z baz± danych, w razie d³u¿szych problemów, spróbuj skontaktowaæ siê z administratorem</div>");
if(!$dbselect) 
	die("<div style='font-size:14px;font-weight:bold;align:center;'>Nie mo¿na wybraæ potrzebnej bazy danych, w razie d³u¿szych problemów, spróbuj skontaktowaæ siê z administratorem</div>");
