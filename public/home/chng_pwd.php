<?php
/*
* Include necessary files
*/
include_once 'core/init.inc.php';
/*
* Set up the page title and CSS files
*/
$page_title = ":: Change Password &rsaquo;&rsaquo; Leave Management System ::";
$common_css_files = array('jquery-ui-1.8.21.custom.css','common.css');
$page_css_files = array('general.css');
$font_awesome_files = array('font-awesome.css', 'prettify.css');
$general_js_files = array('jquery-1.7.2.min.js', 'jquery-ui-1.8.21.custom.min.js', 'slides.min.jquery.js');
$page_js_files = array('general.js', 'common.js');
/*
* Include the header
*/
include_once 'assets/common/header.inc.php';
?>

<div id="content">
<?php
if(isset($_POST['updt_pass']) && $_POST['updt_pass']=='Update Password'){
	$curr_pass = trim($_POST['curr_pass']);
	$new_pass = trim($_POST['new_pass']); 
	$confirm_curr_pass = trim($_POST['confirm_curr_pass']); 
	if($new_pass != $confirm_curr_pass || $new_pass=='' || $confirm_curr_pass==''){
		$err_msg = "The passwords entered do not match!";
	}else{
		$stmt_chk_user = "SELECT eml_adr FROM t_wb_emp WHERE oracle_id=:oracle_id AND pass=:pass";
		$stmt=$dbo->prepare($stmt_chk_user);
		$stmt->execute(array(':oracle_id' => $_SESSION['oracle_id'], ':pass' => sha1( $salt . md5($curr_pass . $salt)) ));

		if($stmt->rowCount()){	//Check if a record is found.
			try{
				/// Update the email in the db
				$stmt_updt_pass = "UPDATE t_wb_emp SET pass=:pass WHERE oracle_id=:oracle_id";
				$stmt_updt_pass=$dbo->prepare($stmt_updt_pass);
				$stmt_updt_pass->execute(array(':pass' => sha1( $salt . md5($new_pass . $salt)), ':oracle_id' =>$_SESSION['oracle_id']));
				
				while ($row = $stmt->fetch ())  {
					$dbemail = $row['eml_adr'];
				}
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				// Additional headers
				$headers .= 'From: Ministry of Science and Tech <no-reply@lasg.gov.ng>' . "\r\n";
				$headers .= 'Bcc:info@lasg.gov.ng' . "\r\n";
				
				$body = "Your password has been reset successfully by you.<br /><br />";
				$body .= "New password: $new_pass<br /><br />";
				$subject  = "[Leave Management System] - Password reset";
				if(mail($dbemail, $subject, $body, $headers)) {
					$success_msg = "Password reset was successful.";
				} else {
					$success_msg .= "Password reset was successful but a notification was could not be sent to your email address.";
				}

			}catch (exception $e){
				$err_msg = "Error: ".$e->getMessage();
			}
		}else{
			@$err_msg .= " The current password entered is incorrect.<br />Please try again later";
		}
	}
	$message = isset($success_msg) ? $success_msg : $err_msg;
	$type = isset($success_msg) ? 'success' : 'err';
}
require_once("assets/common/chng_pwd_form.php");
?>
</div><!-- End content  -->

<?php
/*
* Include the footer
*/
include_once 'assets/common/footer.inc.php';
?>