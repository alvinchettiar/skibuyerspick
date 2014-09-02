<?php
include('database.php');
$json = json_decode($_REQUEST['content_id'], true);
//print_r($json);
foreach($json as $key=>$values)
{

	$id = $values['id'];
	
	
		$sqlDel = mysql_query("update ba_tbl_content set is_deleted = '1' where id = '$id'") or die(mysql_error());
		if(mysql_affected_rows()==1)
		{
			/*** Updating user_space_used by subtracting deleted content size ***/
				/** Selecting content_size **/
				$sqlCon = mysql_query("select vendor_id, content_size from ba_tbl_content where id = '$id'");
				$rowCon = mysql_fetch_assoc($sqlCon);
				$vendor_id = $rowCon["vendor_id"];
				$content_size = $rowCon["content_size"];

				/** getting user_id from vendor master table ***/
				$sqlVenMas = mysql_query("select * from ba_tbl_vendor_master where id = '$vendor_id'");
				$rowVenMas = mysql_fetch_assoc($sqlVenMas);
				$user_id = $rowVenMas["user_id"];

				/*** Selecting user_space_used **/
				$sqlUser = mysql_query("select * from ba_tbl_user where id = '$user_id'");	
				$rowUser = mysql_fetch_assoc($sqlUser);	
				$user_space_used = $rowUser['user_space_used'];

				$new_space = $user_space_used - $content_size;
				$last_modified = date("Y-d-m h:i:s");	
				/** Updating user table with user user_space_used **/
				$sqlUpdate = mysql_query("update ba_tbl_user set user_space_used = '$new_space', last_modified = '$last_modified' where id = '$user_id'");

			/*** END ***/
			/******* Updating is_deleted column from tbl_friend_share table *******/
			$sqlDelFriendShare = mysql_query("update ba_tbl_friend_share set is_deleted = '1' where item_id = '$id'");
			/***************************** END ***********************************/
			$sqlSelect = mysql_query("select id from ba_tbl_content where id = '$id'");
			$rowSelect = mysql_fetch_assoc($sqlSelect);
			$updated_id = $rowSelect["id"];
			$arr_content[] = array("id"=>$updated_id, "last_modified"=>$last_modified);
		}
	
}

if($arr_content==null)
{
		$arr_content = array();	
}	
$new_json['content'] = $arr_content;
$final_json = json_encode($new_json);
print_r($final_json);
?>