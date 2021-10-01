<?php

if( $_GET['option'] == "calendar" AND session_check_usrlevel() >= SPECIAL_LVL )
{
	// session check

	session_check();
	
	// fixing last item id & vars
	
	$sql = "SELECT `id` FROM `".DBPREFIX."calendar` ORDER BY `id` DESC LIMIT 0, 1";
	$query = mysql_query($sql);
	$data = mysql_fetch_assoc($query);
	$last_id = $data['id'];
	
	// new item
	
	if( @$_POST['send'] == "new" )
	{
   		$new['id'] = $last_id+1;
   		$new['date'] = $_POST['y']."-".$_POST['m']."-".$_POST['d'];
   		$new['name'] = addslashes($_POST['form2']);
		$new['cont'] = addslashes($_POST['form3']);
		$new['href'] = addslashes($_POST['form4']);
		$new['type'] = addslashes($_POST['form5']);
		
		if( empty($_POST['form2']) OR empty($_POST['form3']) OR !isset($_POST['form4']) ) $result = "Brak potrzebnych danych";
		else
		{ 
			$sql = "INSERT INTO `".DBPREFIX."calendar` VALUES('".$new['id']."','".$new['date']."','".$new['name']."','".$new['cont']."','".$new['href']."','".$new['type']."');";
			mysql_query($sql);
			$result = "Dodano poprawnie do bazy";
		}
	}
	
	// edit item
	
	if( @$_POST['send'] == "edit" AND check_int($_POST['choosed_id']) == TRUE )
	{
   		$sql = "SELECT * FROM `".DBPREFIX."calendar` WHERE `id` = '".$_POST['choosed_id']."' LIMIT 1";
	   	$query = mysql_query($sql);
	   	if( mysql_num_rows($query) > 0 )
	   	{
				$data = mysql_fetch_assoc($query);
	    		$edit['id'] = $_POST['choosed_id'];
	    		$y = substr($data['date'],0,4); $m = substr($data['date'],5,2);
    			$d = substr($data['date'],8,2);
	    		$edit['name'] = stripslashes($data['name']);
	    		$edit['cont'] = stripslashes($data['content']);
	    		$edit['href'] = stripslashes($data['href']);
	    		$edit['type'] = stripslashes($data['type']);
 		}
		$send_new = "update";
		$action = "Edycja";
		$result = "Wczytano poprawnie";
	}
	
	// update item
	
	if( @$_POST['send'] == "update" )
	{
 		if( check_int($_POST['edited_id']) == TRUE)
 		{
   			$upt['date'] = $_POST['y']."-".$_POST['m']."-".$_POST['d'];
   			$upt['name'] = addslashes($_POST['form2']);
			$upt['cont'] = addslashes($_POST['form3']);
			$upt['href'] = addslashes($_POST['form4']);
			$upt['type'] = addslashes($_POST['form5']);
			
			if( empty($_POST['form2']) OR empty($_POST['form3']) OR !isset($_POST['form4']) ) $result = "Brak potrzebnych danych";
			else
			{ 
   				$sql = "UPDATE `".DBPREFIX."calendar` SET `date` = '".$upt['date']."', `name` = '".$upt['name']."', `content` = '".$upt['cont']."', `href` = '".$upt['href']."', `type` = '".$upt['type']."' WHERE `id` = '".$_POST['edited_id']."'";
		   		$result = "Dane zosta³y zaaktualizowane";
   			}
		}
   		mysql_query($sql);
	}
	
	// fixing date-time
	
	if(!isset($y) OR !isset($m) OR !isset($d)){ $y = date("Y"); $m = date("m"); $d = date("d"); }

	// finish
	
	if( !empty($edit['code']) ) $table_info = "Edytujesz: ".$edit['code']; else $table_info = "Nowa data";
	
	if(!empty($result))
	{
		echo "<div class='suc'>".$result."</div>\n";
	}
	echo "<table class='table0 w100 form-style1'>\n";
	echo "<tr><th colspan='2'>Zarz±dzanie kalendarzem</th></tr>\n";
	echo "<tr><td colspan='2' align='left'><h6>".$table_info."<a href='administration.php' class='right'>Powrót</a></h6><div class='clear'></div></td></tr>\n";
	echo "<tr>";
	echo "<form action='' method='post'>\n";
	echo "<td width='150'>Data</td><td>";
	echo form_select_date("y",2010,2050,"Y",60,$y);
	echo form_select_date("m",1,12,"m",40,$m);
	echo form_select_date("d",1,31,"d",40,$d);
	echo "</td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Nazwa</td><td><input class='text' type='text' name='form2' value='".@$edit['name']."'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Opis</td><td><input class='text' type='text' name='form3' value='".@$edit['cont']."'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Odno¶nik</td><td><input class='text' type='text' name='form4' value='".@$edit['href']."'/></td>";
	echo "</tr>\n";
	echo "<tr>";
	echo "<td>Typ</td><td>\n";
	echo "<select class='select' name='form5' style='width:50px;'>\n";
	echo "<option value='1'"; if( @$edit['type'] == 1 ) echo " selected='selected'"; echo ">1</option>\n";
	echo "<option value='2'"; if( @$edit['type'] == 2 ) echo " selected='selected'"; echo ">2</option>\n";
	echo "<option value='3'"; if( @$edit['type'] == 3 ) echo " selected='selected'"; echo ">3</option>\n";
	echo "</select>\n";
	echo "</td>";
	echo "</tr>\n";
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
	
	echo "<tr><td colspan='2'><h6>Wybierz ju¿ istniej±cy:</h6></td></tr>\n";
	echo "<tr>\n";
	echo "<form action='' method='post'>\n";
	echo "<td align='right'><input type='hidden' name='send' value='edit'/><input type='submit' class='submit' value='OK'/></td>\n";
	echo "<td>\n";
	
	$sql = "SELECT * FROM `".DBPREFIX."calendar` ORDER BY `name` ASC";
	$query = mysql_query($sql);
	if(mysql_num_rows($query)>0);
	{
		echo "  <select name='choosed_id' class='select'>\n";
		while($date = mysql_fetch_assoc($query))
		{
			echo "    <option value='".$date['id']."'>".$date['date']." | ".$date['name']."</option>\n";
		}
		echo "  </select>\n";
	}
	echo "</td>\n";
	echo "</form>\n";
	echo "</tr>\n";
	echo "</table>\n";
}

?>
