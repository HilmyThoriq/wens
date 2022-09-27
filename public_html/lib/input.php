<? 
 if($_SERVER["HTTP_USER_AGENT"] <> "") { 
	if (! $_SESSION["__gm_log_cookie"]){
		
			$__gm_log_cookie = time();
			@session_register("__gm_log_cookie") or die("session_register err");


		$Agent1= $_SERVER["HTTP_USER_AGENT"] ;
		$Agent2= $_SERVER["HTTP_USER_AGENT"] ;

		$botarray =array("yeti","bot","slurp","Googlebot","solaris","panscient","pingdom","WinHttpRequest" ,"Bluecat", "msnbot","NaverBot" ,"ColFeed" , "mutant", "Mediapartners-Google","crawler" ,"xMind","newmoni","libwww","GNUTLS","lgjmd","larbin","libcurl","KDDI"  ,"HttpClient")  ;  
		$Agent2 = str_ireplace($botarray, "" ,$Agent1); 
		$Agentlen1=strlen($Agent1)  ;
		$Agentlen2=strlen( $Agent2)   ;  
		if ($Agentlen1 == $Agentlen2){
			$log=@str_replace($_SERVER["HTTP_HOST"],"",$_SERVER["HTTP_REFERER"] );
			$log=@str_replace("http://www.","",$log);
			$log=@str_replace("http://","",$log);

			$referer		= eregi_replace("^(.{2,6}://)?([^/]*)?(.*)", "\\2", $log);	//방문경로주소
			$refererdetail	= str_replace($referer,"",$log);							//방문경로페이지

			if ($referer == $_SERVER["HTTP_HOST"] ){ $referer = "" ;  	$refererdetail = "" ;  }  
			if($referer  =="" ) $refererdetail = "" ;   

			$lyear	= isnum(date("Y"));
			$lmonth =  isnum(date("m"));
			$lday	=  isnum(date("d"));
			$ltime	=  isnum(date("H"));
			$lweek  =  isnum(date("w")); 

			$qry = "insert into daegu_gm_log(userip,referer,refererdetail,user_agent,lyear,lmonth,lday,ltime,lweek,writeday)values(";
			$qry.= "'".($REMOTE_ADDR)."',";
			$qry.= "'".($referer)."',";
			$qry.= "'".($refererdetail)."',"; 
			$qry.= "'".checkword(substr($_SERVER["HTTP_USER_AGENT"]  , 0 , 250))."',";
			$qry.= "'".($lyear)."',";
			$qry.= "'".($lmonth)."',";
			$qry.= "'".($lday)."',";
			$qry.= "'".($ltime)."',";
			$qry.= "'".($lweek)."' , ";
			$qry.= "now()";
			$qry.= ")";
			 @dbquery($qry)  ;
	}

}
 
 
}
 ?>