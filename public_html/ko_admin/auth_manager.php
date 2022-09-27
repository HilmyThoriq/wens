<?
	//if(($_SESSION['auth_level'] != 1) alert_to_admin("올바른 접근이 아닙니다. \\n\\n본사이트의 비정상적인 접근은 법적 불이익을 받으실 수 있습니다.\\n\\n - not level");
	if(!$_SESSION['auth_admin_ci']) {
		alert_to_admin("올바른 접근이 아닙니다. \\n\\n본사이트의 비정상적인 접근은 법적 불이익을 받으실 수 있습니다.\\n\\n - not auth_admin_ci");
		exit;
	}
	if(!$_SESSION['auth_admin_di']) {
		alert_to_admin("올바른 접근이 아닙니다. \\n\\n본사이트의 비정상적인 접근은 법적 불이익을 받으실 수 있습니다.\\n\\n - not auth_admin_di");
		exit;
	}
	if(!$_SESSION['auth_token']){
		alert_to_admin("올바른 접근이 아닙니다. \\n\\n본사이트의 비정상적인 접근은 법적 불이익을 받으실 수 있습니다.\\n\\n - not auth_token");
		exit;
	}
	if(!$_SESSION['is_admin']){
		alert_to_admin("올바른 접근이 아닙니다. \\n\\n본사이트의 비정상적인 접근은 법적 불이익을 받으실 수 있습니다.\\n\\n - not admin");
		exit;
	}
?>

