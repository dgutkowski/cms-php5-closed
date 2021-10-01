<?php
require_once "maincore.php";
require_once (BASEDIR.STYLE."tpl.start.php");
log_user_out();
redirect($config['mainpage']);
require_once (BASEDIR.STYLE."tpl.end.php");
?>
