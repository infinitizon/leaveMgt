<?php
/*
* Include necessary files
*/
include_once 'core/init.inc.php';

$profile = new Profile($dbo);
$prof_det = $profile->_getProfile($_GET['usr']);
$fxns = new Functions($dbo);

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
// Additional headers
$headers .= 'From: Ministry of Science and Tech <no-reply@lasg.gov.ng>' . "\r\n";
$headers .= 'Bcc:info@lasg.gov.ng' . "\r\n";

$leave_dtl = $fxns->_getThisLeave($_GET['lv_id']);
$leave_tp = $fxns->_getLOVDsc('t_wb_lv_tp_lov', $leave_dtl['lv_tp']);
//Get all your input for this record
$stmt_chk_prv_appr ="SELECT appr_stat FROM t_wb_lv_apl_dtl
					WHERE lv_apl_ky=:lv_apl_ky AND appr_lvl=:appr_lvl";
$stmt_chk_prv_appr=$dbo->prepare($stmt_chk_prv_appr);
$stmt_chk_prv_appr->execute( array(':lv_apl_ky' => $_GET['lv_id'], ':appr_lvl' =>$_GET['appr_lv']) );

if ($_GET['appr'] == 1){			
	if($stmt_chk_prv_appr->rowCount()){	//Record was already approved.
		$err_msg = "This record has already been processed by you. No further processing required here";
	}else{
		try{
			$stmt_chk_prv_appr ="INSERT INTO t_wb_lv_apl_dtl (lv_apl_ky, appr_lvl, appr_stat)
								VALUES (:lv_apl_ky, :appr_lvl, :appr_stat)";
			$stmt=$dbo->prepare($stmt_chk_prv_appr);
			$stmt->execute( array(':lv_apl_ky' => $_GET['lv_id'], ':appr_lvl' =>$_GET['appr_lv'], ':appr_stat' => $_GET['appr']) );

			$nxt_appr_lv = $_GET['appr_lv']+1;
			if ($fxns->_getApprCnt($_GET['usr']) > $_GET['appr_lv']){  //Does this guy have other supervsors? yes, notfy next
				$appr = $fxns->_getApprover($_GET['usr'], $nxt_appr_lv);  //Get next approver
				extract($appr);
			
				$toMsg = "A <b>".$leave_tp."</b> request for ".$leave_dtl['days_apld']."days was made by <b>".$usr_nm."</b>\r\n<br />";
				$toMsg .= "Reason: ".$leave_dtl['lv_rsn']."<br /><br />";
				$toMsg .= "Click on the links below to either approve or reject<br /><br />";
				$toMsg .= '<div style="margin-left:150px;">';
				$toMsg .= '<a href="'.WEB_ROOT.'/lv_process.php?lv_id='.$leave_dtl['r_k'].'&usr='.$leave_dtl['oracle_id'].'&appr_lv='.$nxt_appr_lv.'&appr=1" style="background:#06F; padding:5px 10px; text-decoration:none;">Approve</a> &nbsp;&nbsp;&nbsp;&nbsp;';
				$toMsg .= '<a href="'.WEB_ROOT.'/lv_process.php?lv_id='.$leave_dtl['r_k'].'&usr='.$leave_dtl['oracle_id'].'&appr_lv='.$nxt_appr_lv.'&appr=0" style="background:#F60; padding:5px 10px; text-decoration:none;">Reject</a></div><br /><br />';
				$toMsg .= "You can also login to your account to either approve or reject the request";
				if(mail($appr_eml, "New Leave Request", $toMsg, $headers)) {
					$success_msg = "Leave request sent.";
				} else {
					$success_msg = "Your leave request has been made but a notification could not be sent to your supervisor<br />You may need to contact your supervisor in person and notify him/her of the request";
				}
				$toMsg = "Your ".$leave_tp." has just been approved at level ".$_GET['appr_lv']."<br /><br />";
				$toMsg .= "A notification will now be sent to your next level supervisor for approval";
				if(mail($prof_det['eml_adr'], "Leave approved at level ".$_GET['appr_lv'], $toMsg, $headers)) {
					$success_msg .= "$usr_nm has also been notified.";
				} else {
					$success_msg .= "$usr_nm however could not be notified";
				}				
			}else{//This is the final approver, so notify user of final approval
				$toMsg = "Your ".$leave_tp." has just been approved at level ".$_GET['appr_lv']."<br /><br />";
				$toMsg .= "This is the final approval needed. You may now proceed on the leave at the specified date";
				if(mail($prof_det['eml_adr'], "Leave approved at level ".$_GET['appr_lv'], $toMsg, $headers)) {
					$success_msg = "Mail approved successfully.".$prof_det['fst_nm'].' '.$prof_det['lst_nm']." has be notified";
				} else {
					$success_msg = "Mail approved successfully. However,".$prof_det['fst_nm'].' '.$prof_det['lst_nm']." could not be notified";
				}				
				$stmt_appr ="UPDATE t_wb_lv_apl SET apr_stat=:appr_stat
									WHERE r_k =:lv_apl_ky";
				$stmt=$dbo->prepare($stmt_appr);
				$stmt->execute( array(':lv_apl_ky' => $_GET['lv_id'], ':appr_stat' => $_GET['appr']) );
			}
		}catch (exception $e){
			$err_msg = "Error: ".$e->getMessage();
		}
	}
}elseif ($_GET['appr'] == 0){
			
	if($stmt_chk_prv_appr->rowCount()){	//Record was already approved.
		$err_msg = "This record has already been processed by you. No further processing required";
	}else{
		$stmt_chk_prv_appr ="INSERT INTO t_wb_lv_apl_dtl (lv_apl_ky, appr_lvl, appr_stat)
							VALUES (:lv_apl_ky, :appr_lvl, :appr_stat)";
		$stmt=$dbo->prepare($stmt_chk_prv_appr);
		$stmt->execute( array(':lv_apl_ky' => $_GET['lv_id'], ':appr_lvl' =>$_GET['appr_lv'], ':appr_stat' => '2') );
####################################
		$stmt_deny ="UPDATE t_wb_lv_apl SET apr_stat=:apr_stat
							WHERE r_k =:lv_apl_ky";
		$stmt=$dbo->prepare($stmt_deny);
		$stmt->execute( array(':lv_apl_ky' => $_GET['lv_id'], ':apr_stat' => '2') );
####################################
		$toMsg = "Your ".$leave_tp." has just been rejected at level ".$_GET['appr_lv']."<br /><br />";
		$toMsg .= "You need to restart the application process if you really want to go on this leave.";
		if(mail($prof_det['eml_adr'], "Leave rejected at level ".$_GET['appr_lv'], $toMsg, $headers)) {
			$success_msg = "Mail rejected successfully.".$prof_det['fst_nm'].' '.$prof_det['lst_nm']." has be notified";
		} else {
			$success_msg = "Mail rejected successfully. However,".$prof_det['fst_nm'].' '.$prof_det['lst_nm']." could not be notified";
		}
	}
}elseif ($_GET['appr'] == 3){
	if($stmt_chk_prv_appr->rowCount()){	//Record was already approved.
		$err_msg = "This record has already been processed by you. No further processing required";
	}else{
		try{
			$stmt_chk_prv_appr ="INSERT INTO t_wb_lv_apl_dtl (lv_apl_ky, appr_lvl, appr_stat)
								VALUES (:lv_apl_ky, :appr_lvl, :appr_stat)";
			$stmt=$dbo->prepare($stmt_chk_prv_appr);
			$stmt->execute( array(':lv_apl_ky' => $_GET['lv_id'], ':appr_lvl' => 0, ':appr_stat' => '3') );
	####################################
			$stmt_deny ="UPDATE t_wb_lv_apl SET apr_stat=:apr_stat
								WHERE r_k =:lv_apl_ky";
			$stmt=$dbo->prepare($stmt_deny);
			$stmt->execute( array(':lv_apl_ky' => $_GET['lv_id'], ':apr_stat' => '3') );
	####################################
			$success_msg = "Leave request cancelled successfully by you.";
		}catch (exception $e){
			$err_msg = "Error: ".$e->getMessage();
		}
	}
}
$notification = isset($success_msg) ? $success_msg : $err_msg;
$type = isset($success_msg) ? 'success' : 'err';
if($_SESSION['emp_tp'] < 2){
	$goto_page = "/home/leavemgt.php?my_lvs=all&message=".$notification."&type=".$type;
}elseif($_SESSION['emp_tp'] >= 2){
	$goto_page = "/home/leavemgt.php?sn=1&message=".$notification."&type=".$type;
}else{
	$goto_page = "";
}
header('Location: http://'.$_SERVER['HTTP_HOST'].$goto_page );
?>
