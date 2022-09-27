<?
/*----------------------------------------------------------------------------*/
include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/config_setup.php";  
include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/auth_manager.php";  
/*----------------------------------------------------------------------------*/
$query = "SELECT * FROM koweb_page_metatag WHERE url = '$variable' ORDER BY no DESC";
$result = mysql_query($query);
$row = mysql_fetch_array($result);
if(!$row[0]) $json = array("url"=>$variable);
else $json = array("url"=>$row[url], "description"=>$row[description],"og_description"=>$row[og_description],"og_site_name"=>$row[og_site_name],"og_title"=>$row[og_title]); 
echo json_encode($json);