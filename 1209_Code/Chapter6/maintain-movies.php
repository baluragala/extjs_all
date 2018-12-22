<?php

Include('../../cfg/db.php');

$action  = ($_REQUEST['action'] != '') ? $_REQUEST['action'] : 'update';

$arr = array();
Switch ($action){
    Case 'create':
        $sql = "INSERT INTO movies (id,title,director,genre,tagline) VALUES (0,'".$_REQUEST['title']."','',0,'')";
    Break;
    Case 'read':
        $sql = "SELECT * FROM movies";
    Break;
    Case 'update':
        $sql = "UPDATE movies SET ".$_REQUEST['field']." = '".$_REQUEST['value']."' WHERE id = ".$_REQUEST['id'];
    Break;
	Case 'delete':
		$sql = "DELETE FROM movies WHERE id = ".$_REQUEST['id'];
	Break;
}
header('Content-Type: application/json');
If (!$rs = mysql_query($sql)) {
	Echo '{success:false,message:"'.mysql_error().'"}';
}else{
    Switch ($action){
        Case 'create':
            Echo '{success:true,rows:'.json_encode($rs).'}';
        Break;
        Case 'read':
            while($obj = mysql_fetch_object($rs)){
                $arr[] = $obj;
            }
            Echo '{success:true,rows:'.json_encode($arr).'}';
        Break;
        Case 'update':
            $sql = "SELECT * FROM movies WHERE id = ".$_REQUEST['id'];
            If (!$rs = mysql_query($sql)) {
                Echo '{success:false}';
            } else {
                while($obj = mysql_fetch_object($rs)){
                    $arr[] = $obj;
                }
                Echo '{success:true,rows:'.json_encode($arr).'}';
            }
        Break;
        Case 'delete':
            Echo '{success:true}';
        Break;
    }
}

?>
