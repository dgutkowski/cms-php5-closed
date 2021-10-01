<?php

if( $_GET['option'] == "bans" AND session_check_usrlevel() >= SPECIAL_LVL )
{	

	// session check

	session_check();
	
	// fixing last item id & vars
	
	$sql = "SELECT `id` FROM `".DBPREFIX."blacklist` ORDER BY `id` DESC LIMIT 0, 1";
	$query = mysql_query($sql);
	$data = mysql_fetch_assoc($query);
	$last_id = $data['id'];
	
	$ys = date("Y"); $ms = date("m"); $ds = date("d");
	$hs = date("H"); $is = date("i"); $ss = "00";
	$ye = date("Y",strtotime ("+1 hour")); $me = date("m",strtotime ("+1 hour")); $de = date("d",strtotime ("+1 hour"));
	$he = date("H",strtotime ("+1 hour")); $ie = date("i",strtotime ("+1 hour")); $se = "00";
	
	// new ban
	
	$table_info = "Nowy";
	
	if ( @$_POST['send'] == "new" )
	{
   		if( !empty($_POST['form1']) OR !empty($_POST['form2']) )
	   	{
	  		$user_no_exists = 0;
	    		if ( !empty($_POST['form1']) )
	    		{
	  			if( db_get_data("id","users","login",$_POST['form1']) != $config['headadmin'] )
	  			{
	      				$sql = "SELECT * FROM `".DBPREFIX."users` WHERE `login` = '".htmlspecialchars(addslashes($_POST['form1']))."'";
		      			$query = mysql_query($sql);
		      			if( mysql_num_rows($query) > 0 )
		      			{
				    		$data = mysql_fetch_assoc($query);
				    		$selected_id = $data['id'];
		  			}
			  		else { $user_no_exists = 1; $result = "Nie znaleziono podanego u¿ytkownika"; }
		  		}
		  		elseif( db_get_data("id","users","login",$_POST['form1']) == $config['headadmin'] )
		  		{
					$user_no_exists = 1; $result = "Nie mo¿na zablokowaæ g³ównego administratora!!!"; 
				}
			}
			if( $user_no_exists != 1 )
			{
		  		$new['id'] = $last_id+1;
				$new['userid'] = db_get_data("id","users","login",$_POST['form1']);
		  		$new['ip'] = addslashes($_POST['form2']);
		  		$new['reason'] = addslashes($_POST['form3']);
	 			$new['date_start'] = $_POST['ys']."-".$_POST['ms']."-".$_POST['ds']." ".$_POST['hs'].":".$_POST['is'].":".$_POST['ss'];
				$new['date_end'] = $_POST['ye']."-".$_POST['me']."-".$_POST['de']." ".$_POST['he'].":".$_POST['ie'].":".$_POST['se'];
				
				if( $new['date_start'] < $new['date_end'] )
				{	
		    			$sql = "INSERT INTO `".DBPREFIX."blacklist` VALUES('".$new['id']."','".$new['userid']."','".$new['ip']."','".$new['reason']."','".$new['date_start']."','".$new['date_end']."','".$_SESSION['userid']."','".USER_IP."','".DATETIME."');";
					mysql_query($sql);
					$result = "Dodano blokadê";
				}
				else $result = "Data zakoñczenia musi byæ po dacie rozpoczêcia!";
	  		}
		}
		else $result = "Brak danych dotycz±cych blokady";
	}
	
	// edit ban
	
	if ( @$_POST['send'] == "edit" )
	{
   		$sql = "SELECT * FROM `".DBPREFIX."blacklist` WHERE `id` = '".$_POST['ban_id']."' LIMIT 1";
	   	$query = mysql_query($sql);
	   	if ( mysql_num_rows($query) > 0 )
	   	{
    			$a = mysql_fetch_assoc($query);
	    		
	    		$edit['userid'] = db_get_data("login","users","id",$a['ban_userid']);
	    		$edit['ip'] = $a['ban_ip'];
	    		$edit['reason'] = $a['ban_reason'];
	    		
	    		$edit['i1'] = $a['adds'];
	    		$edit['i2'] = $a['adds_ip'];
	    		$edit['i3'] = $a['date'];
	    		
	    		$table_info = "Edycja blokady dla: ".$edit['userid']." | IP: ";
			if( !empty($edit['ip']) ) $table_info .= $edit['ip']; else $table_info .= " n/a ";
	    		
	    		$ys = substr($a['ban_start'],0,4); $ms = substr($a['ban_start'],5,2);
	    		$ds = substr($a['ban_start'],8,2); $hs = substr($a['ban_start'],11,4);
  			$is = substr($a['ban_start'],14,2); $ss = substr($a['ban_start'],17,2);
	  			
  			$ye = substr($a['ban_end'],0,4); $me = substr($a['ban_end'],5,2);
	    		$de = substr($a['ban_end'],8,2); $he = substr($a['ban_end'],11,4);
  			$ie = substr($a['ban_end'],14,2); $se = substr($a['ban_end'],17,2); 
			  
			$edit['id'] = $a['id'];	
			
			$send_new = "update";
			$action = "Edycja";
			$result = "Wczytano poprawnie";
 		}
	}
	
	// update ban
	
	if ( @$_POST['send'] == "update" )
	{
		if( !empty($_POST['form1']) OR !empty($_POST['form2']) )
	   	{
	  		$user_no_exists = 0;
	    		if ( !empty($_POST['form1']) )
	    			{
	      				$sql = "SELECT * FROM `".DBPREFIX."users` WHERE `login` = '".htmlspecialchars(addslashes($_POST['form1']))."'";
		      			$query = mysql_query($sql);
		      			if( mysql_num_rows($query) > 0 )
	      				{
				    		$data = mysql_fetch_assoc($query);
				    		$selected_id = $data['id'];
			  		}
			  		elseif( empty($_POST['form2']) ) { $user_no_exists = 1; $result = "Nie znaleziono podanego u¿ytkownika"; }
				}
				if( htmlspecialchars(addslashes($_POST['form1'])) == $config['headadmin'] )
	 			{
					$user_no_exists = 1; $result = "Nie mo¿na zablokowaæ g³ównego administratora!!!";
				}
				if( $user_no_exists != 1 )
				{
		  			$upt['userid'] = db_get_data("id","users","login",$_POST['form1']);
		  			$upt['ip'] = addslashes($_POST['form2']);
		  			$upt['reason'] = addslashes($_POST['form3']);
	 				$upt['date_start'] = $_POST['ys']."-".$_POST['ms']."-".$_POST['ds']." ".$_POST['hs'].":".$_POST['is'].":".$_POST['ss'];
					$upt['date_end'] = $_POST['ye']."-".$_POST['me']."-".$_POST['de']." ".$_POST['he'].":".$_POST['ie'].":".$_POST['se'];
					
					if( $upt['date_start'] < $upt['date_end'] )
					{
		    				$sql = "UPDATE `".DBPREFIX."blacklist` SET `ban_userid` = '".$upt['userid']."', `ban_ip` = '".$upt['ip']."', `ban_reason` = '".$upt['reason']."', `ban_start` = '".$upt['date_start']."', `ban_end` = '".$upt['date_end']."' WHERE `id` = '".$_POST['edited_id']."' LIMIT 1";
					 	mysql_query($sql);
					 	$result = "Zaaktualizowano";
				 	}
				 	else $result = "Data zakoñczenia musi byæ po dacie rozpoczêcia!";
		  		}
		}
		else $result = "Brak danych dotycz±cych blokady";
	}
	
	if( !empty($result) ) echo "<div class='not'>".$result."</div>\n";
		
	$valid_unban = date("Y-m-d H:i:s",strtotime("-1 month"));
	
	echo "<table class='table0 w100 form-style1'>\n";
	echo "<tr><th colspan='5'>Zarz±dzanie list± blokad</th></tr>\n";
	
	
	$sql = "SELECT * FROM `".DBPREFIX."blacklist` WHERE `ban_end` > '".$valid_unban."'";
	$query = mysql_query($sql);
	if( mysql_num_rows($query) > 0 )
	{
	   	echo "<tr><td colspan='5'><h6>Istniej±ce blokady<a href='administration.php' class='right'>Powrót</a></h6><div class='clear'></div></td></tr>\n";
		echo "<tr>";
	   	echo "<td width='150'><h6 class='center'>U¿ytkownik</h6></td>";
	   	echo "<td width='110'><h6 class='center'>IP</h6></td>";
	   	echo "<td width='150'><h6 class='center'>Powód</h6></td>";
	   	echo "<td width='140'><h6 class='center'>Czas blokady</h6></tdh>";
	   	echo "<td width='50'></td>";
	   	echo "</tr>\n";
	   	while ( $ban = mysql_fetch_assoc($query) )
	   	{
			echo "<tr>";
			echo "<form action='' method='post'>\n";
			echo "<td class='center'>".db_get_data("login","users","id",$ban['ban_userid'])."</td>";
			echo "<td class='center'>".$ban['ban_ip']."</td>";
			echo "<td class='center'>".$ban['ban_reason']."</td>";
			echo "<td>"; if( $ban['ban_end'] > DATETIME ) echo "<b>OD:</b> ".$ban['ban_start']."<br/>"; else echo "<b class='green'>Zakoñczony</b><br/>"; echo "<b>DO:</b> ".$ban['ban_end']."</td>";
			echo "<td><input type='hidden' name='send' value='edit'><input type='hidden' name='ban_id' value='".$ban['id']."'><input type='submit' class='submit-bans' value='Zmieñ'/>";
			echo "</form>\n";
			echo "</tr>\n";
		}
	}
	else
	{
		echo "<tr><td colspan='5' align='left'><h6><a href='administration.php' class='right'>Powrót</a></h6><div class='clear'></div></td></tr>\n";
	}
	

	echo "<tr><td colspan='5' align='left'><h6>";
	if ( !empty($table_info) ) echo $table_info; else echo "&nbsp;";
	echo "</td></tr>\n";
	echo "<tr>";
	echo "<form action='' method='post'>\n";
	echo "<td width='150'>U¿ytkownik</td><td colspan='4'><input class='text' type='text' name='form1' value='".@$edit['userid']."'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>IP</td><td colspan='4'><input class='text' type='text' name='form2' value='".@$edit['ip']."'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Powód</td><td colspan='4'><input class='text' type='text' name='form3' value='".@$edit['reason']."'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td width='150'>Data rozpoczêcia</td><td colspan='4'>";
	echo form_select_date("ys",2005,2090,"Y",60,$ys);
	echo form_select_date("ms",1,12,"m",40,$ms);
	echo form_select_date("ds",1,31,"d",40,$ds);
	echo " : ";
	echo form_select_date("hs",0,23,"H",40,$hs);
	echo form_select_date("is",0,59,"i",40,$is);
	echo form_select_date("ss",0,59,"s",40,$ss);
	echo "</td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td width='150'>Data zakoñczenia</td><td colspan='4'>";
	echo form_select_date("ye",2005,2090,"Y",60,$ye);
	echo form_select_date("me",1,12,"m",40,$me);
	echo form_select_date("de",1,31,"d",40,$de);
	echo " : ";
	echo form_select_date("he",0,23,"H",40,$he);
	echo form_select_date("ie",0,59,"i",40,$ie);
	echo form_select_date("se",0,59,"s",40,$se);
	echo "</td>";
	echo "</tr>\n";
	if( (!empty($edit['i1']) OR !empty($edit['i2']) OR !empty($edit['i3'])) AND $config['headadmin'] == $_SESSION['userid'] )
	{
		echo "<tr><td>Autor</td><td colspan='4'>".db_get_data("login","users","id",$edit['i1'])."</td></tr>\n";
		echo "<tr><td>IP</td><td colspan='4'>".$edit['i2']."</td></tr>\n";
		echo "<tr><td>Data</td><td colspan='4'>".$edit['i3']."</td></tr>\n";
	}
	echo "<tr>";
	echo "<td>";
	if( @$_POST['send'] == "new" OR @$_POST['send'] == "update" ) { $send_type = "new"; }
	if( @!empty($_POST['send']) && @$_POST['send'] == "edit" ) echo "<input type='hidden' name='edited_id' value='".$edit['id']."'/>";
	if( isset($send_new) )
	{
		$send_type = $send_new;
	}
	else $send_type = "new";
	echo "<input type='hidden' name='send' value='".$send_type."'/></td><td><input class='submit' type='submit' value='Wy¶lij'/></td>";
	echo "</form>\n";
	echo "</tr>\n";
	echo "</table>\n\n";	
}

?>
