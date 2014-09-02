<?php
include_once('database.php');
if(isset($_REQUEST['id']) && $_REQUEST['id']!="")
{
	
	
	$id = $_REQUEST['id'];
	$sqlSelect = mysql_query("select * from ba_tbl_user where id = '$id'");
	$rowSelect = mysql_fetch_assoc($sqlSelect);

	/****** getting tbl_user *******/	
	$user_master_id = $rowSelect['id'];
	
	$user_space_used = $rowSelect['user_space_used'];

	
	$arr_user_details[] = array("id"=>$user_master_id, "user_space_used"=>$user_space_used);

	$data["user"] = $arr_user_details;
	$final_data = json_encode($data);
	print_r($final_data);
}

?>