<?php
include_once 'core/init.inc.php';
if($_POST['pk']){
	
	try{
		$stmt_updt_recd = "UPDATE t_wb_emp SET {$_POST['name']}=:name_value WHERE oracle_id=:pk";
		$stmt=$dbo->prepare($stmt_updt_recd);
		$stmt->execute(array(':name_value' => $_POST['value'], ':pk' => $_POST['pk']));
		//If there was an update on the matric no field
		if($_POST['name'] == 'matric_no'){
			$stmt_mail_Stud = "SELECT first_name, last_name, email FROM students_reg WHERE student_reg_id=:pk";
			$stmt=$dbo->prepare($stmt_mail_Stud);
			$stmt->execute(array(':pk' => $_POST['pk']));
			$results = $stmt->fetch(PDO::FETCH_ASSOC);

			//Send a mail to the User
			require($_SERVER['DOCUMENT_ROOT'].'/assets/inc/fxns.php');
			$to		= $results['email'];
			$subject	= 'Matric No. Updated on NIS Student portal';
			$message = 'Hi '.$results['first_name'].' '.$results['last_name'];
			$message = ', <br />Your Matric No. has been recently updated on the NIS Student Portal as follows:<br />';
			$message .= 'Matric No: ' . $_POST['value'] .'<br />';
			$message .= 'Password has been modified to: ' . randomPassword(8) . '<br /><br /><br />';
			$message .= 'Note: You can not reply to this mail because it is an automatically generated mail';
		
			$headers = 'From: no_reply@nisport.gov.ng' . "\r\n" .
							'Reply-To: no_reply@nisport.gov.ng' . "\r\n" .
							'MIME-Version: 1.0' . "\r\n" .
							'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
							'X-Mailer: PHP/' . phpversion();
			mail($to, $subject, $message, $headers);
		}
	}catch(exception $e){
		echo $e->getMessage();
	}
}
?>