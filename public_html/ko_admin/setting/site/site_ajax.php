<? 
	include_once $_SERVER['DOCUMENT_ROOT'] . "/head.php";
	$setting_table = "koweb_site_config";
	//기본정보
	$default = mysql_fetch_array(mysql_query("SELECT * FROM koweb_site_config WHERE lang = '$lang' ORDER BY no DESC LIMIT 1"));


	$result_array = array("title" => $default[title]
					,"lang" => $default[lang] 
					,"site_url" => $default[site_url] 
					,"description" => $default[description] 
					,"og_description" => $default[og_description] 
					,"og_site_name" => $default[og_site_name] 
					,"og_title" => $default[og_title] 
			 );

	$result = json_encode($result_array);
	echo($result);
?>
