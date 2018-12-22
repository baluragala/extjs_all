<?php

Include('../../cfg/db.php');

$action  = ($_REQUEST['action'] != '') ? $_REQUEST['action'] : 'update';

$arr = array();
$rec = array();
Switch ($action){
    Case 'create':
        $rec = json_decode($_REQUEST['data'], true);
        $rel = date_create_from_format("Y-m-d\TH:i:s", $rec['released']);
        $rel = ($rel != '') ? date_format($rel, "Y-m-d") : '';
        $sql = "INSERT INTO movies (id,title,director,released,runtime,genre,tagline) VALUES (".
            "0,".
            "'".$rec['title']."',".
            "'".$rec['director']."',".
            "'".$rel."',".
            $rec['runtime'].",".
            $rec['genre'].",".
            "'".$rec['tagline']."'".
        ")";
    Break;
    Case 'read':
        $sql = "SELECT * FROM movies";
    Break;
    Case 'update':
        $rec = json_decode($_REQUEST['data'], true);
        $rel = date_create_from_format("Y-m-d\TH:i:s", $rec['released']);
        $rel = ($rel != '') ? date_format($rel, "Y-m-d") : '';
        $sql = "UPDATE movies SET ".
            "title='".$rec['title']."',".
            "director='".$rec['director']."',".
            "released='".$rel."',".
            "runtime=".$rec['runtime'].",".
            "genre=".$rec['genre'].",".
            "tagline='".$rec['tagline']."' WHERE id = ".$rec['id'];
    Break;
	Case 'destroy':
		$sql = "DELETE FROM movies WHERE id = ".$_REQUEST['data'];
	Break;
}
header('Content-Type: application/json');
If (!$rs = mysql_query($sql)) {
	Echo '{success:false,message:"'.mysql_error().'"}';
}else{
    Switch ($action){
        Case 'create':
            $sql = "SELECT * FROM movies WHERE id = ".mysql_insert_id();
            If (!$rs = mysql_query($sql)) {
                Echo '{success:false,message:"'.mysql_error().'"}';
            } else {
                while($obj = mysql_fetch_object($rs)){
                    $arr[] = $obj;
                }
                Echo '{success:true,data:'.json_encode($arr).'}';
            }
        Break;
        Case 'read':
            while($obj = mysql_fetch_object($rs)){
                $arr[] = $obj;
            }
            Echo '{success:true,data:'.json_encode($arr).'}';
        Break;
        Case 'update':
            $sql = "SELECT * FROM movies WHERE id = ".$rec['id'];
            If (!$rs = mysql_query($sql)) {
                Echo '{success:false}';
            } else {
                while($obj = mysql_fetch_object($rs)){
                    $arr[] = $obj;
                }
                Echo '{success:true,data:'.json_encode($arr).'}';
            }
        Break;
        Case 'destroy':
            Echo '{success:true}';
        Break;
    }
}
?>