<?php

Include('../../cfg/db.php');

$action  = ($_REQUEST['action'] != '') ? $_REQUEST['action'] : 'update';

$arr = array();
Switch ($action){
    Case 'create':
        $sql = "INSERT INTO movies (id,title,director,genre,tagline) VALUES (0,'".$_REQUEST['title']."','',0,'')";
    Break;
    Case 'update':
        $sql = "UPDATE movies SET ".$_REQUEST['field']." = '".$_REQUEST['value']."' WHERE id = ".$_REQUEST['id'];
    Break;
	Case 'destroy':
		$sql = "DELETE FROM movies WHERE id = ".$_REQUEST['id'];
	Break;
}
header('Content-Type: application/json');
If (!$rs = mysql_query($sql)) {
	Echo '{success:false,message:"'.mysql_error().'"}';
}else{
    Switch ($action){
        Case 'create':
            Echo '{success:true,insert_id:'.mysql_insert_id().'}';
        Break;
        Case 'update':
            Echo '{success:true}';
        Break;
        Case 'destroy':
            Echo '{success:true}';
        Break;
    }
}
?>