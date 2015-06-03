<?php
/*
* Include necessary files
*/
include_once 'core/init.inc.php';
/*
* Set up the page title and CSS files
*/
$page_title = ":: Register Courses &rsaquo;&rsaquo; National Institute For Sports ::";
$common_css_files = array('jquery-ui-1.8.21.custom.css','common.css');
$page_css_files = array('general.css', 'jqueryui-editable.css');
$font_awesome_files = array('font-awesome.css');
$general_js_files = array('jquery-1.7.2.min.js', 'jquery-ui-1.8.21.custom.min.js');
$page_js_files = array('common.js');
/*
* Include the header
*/
include_once 'assets/common/header.inc.php';
$profile = new Profile($dbo);
$prof_det = $profile->_getProfile(@$_SESSION['oracle_id']);
$fxns = new Functions($dbo);
if(@$_GET['sn']){
	echo "<h2>Leave Applications</h2>";
	echo "Filter: ";
	echo $fxns->_getLOVs('filter', '00-STAT', 'filter', '--Select leave type--', @$_GET['filter']);
	$lv_aplcatn = $fxns->_getleaveReqs(@$_GET['filter']);
	$lv_aplcatns = "<br /><br /><table border='1' class='myTables' rules='all' cellpadding='5'>\n";
	$lv_aplcatns .= "<tr><th>Name</th><th>Leave Type</th><th>Days Applies</th><th>Leave Status</th><th>Department</th><th>Ministry</th><th>&nbsp;</th></tr>";
	if( count($lv_aplcatn) ){
		$appr_lv = $_SESSION['emp_tp'] - 1;
		foreach($lv_aplcatn as $index => $row){
			$lv_aplcatns .="<tr>";
			$lv_aplcatns .='<td><a href="'.WEB_ROOT.'/home/leavemgt.php?lv_id='.$row['lv_id'].'&view=1" class="genBtn">View</a></td>';
			if($_SESSION['emp_tp'] ==2){
				if( $row['dept'] == $prof_det['dept']){
					$lv_aplcatns .="<td>".$row['lst_nm'].' '.$row['fst_nm']."</td>";
					$lv_aplcatns .= "<td>".$row['lv_tp']."</td>";
					$lv_aplcatns .= "<td>".$row['days_apld']."</td>";
					$lv_aplcatns .= "<td>".$row['apr_stat_dsc']."</td>";
					$lv_aplcatns .= "<td>".$row['dept']."</td>";
					$lv_aplcatns .= "<td>".$row['mstry']."</td>";
					if($row['apr_stat_dm'] == 0){
						$lv_aplcatns .= '<td><a href="'.WEB_ROOT.'/lv_process.php?lv_id='.$row['lv_id'].'&usr='.$row['oracle_id'].'&appr_lv='.$appr_lv.'&appr=1" class="goodBtn">Approve</a> <a href="'.WEB_ROOT.'/lv_process.php?lv_id='.$row['lv_id'].'&usr='.$row['oracle_id'].'&appr_lv='.$appr_lv.'&appr=0" class="failBtn">Reject</a></td>';
			}
				}
			}else{
					$lv_aplcatns .="<td>".$row['lst_nm'].' '.$row['fst_nm']."</td>";
					$lv_aplcatns .= "<td>".$row['lv_tp']."</td>";
					$lv_aplcatns .= "<td>".$row['days_apld']."</td>";
					$lv_aplcatns .= "<td>".$row['apr_stat_dsc']."</td>";
					$lv_aplcatns .= "<td>".$row['dept']."</td>";
					$lv_aplcatns .= "<td>".$row['mstry']."</td>";
					if($row['apr_stat_dm'] == 0){
						$lv_aplcatns .= '<td><a href="'.WEB_ROOT.'/lv_process.php?lv_id='.$row['lv_id'].'&usr='.$row['oracle_id'].'&appr_lv='.$appr_lv.'&appr=1" class="goodBtn">Approve</a> <a href="'.WEB_ROOT.'/lv_process.php?lv_id='.$row['lv_id'].'&usr='.$row['oracle_id'].'&appr_lv='.$appr_lv.'&appr=0" class="failBtn">Reject</a></td>';
					}
			}
			$lv_aplcatns .= "</tr>\n";
		}
	}else{
		$lv_aplcatns .='<td colspan="8" align="center">No leave applications found</td>';
	}
	$lv_aplcatns .= "</table>\n";
	echo $lv_aplcatns;
}elseif(@$_GET['view']){
	$dsLeave = $fxns->_getThisLeave(@$_GET['lv_id']);
	require_once("assets/common/leave_apl_form.php");
}elseif(@$_GET['my_lvs']){
	$lv_aplcatn = $fxns->_getleaveReqs(NULL, true);
	$lv_aplcatns = "<br /><br /><table border='1' class='myTables' rules='all' cellpadding='5'>\n";
	$lv_aplcatns .= "<tr><th>Leave Type</th><th>Days Applies</th><th>Leave Status</th><th>Department</th><th>Ministry</th><th>&nbsp;</th></tr>";
	foreach($lv_aplcatn as $index => $row){
		$lv_aplcatns .="<tr>";
		if($_SESSION['oracle_id'] == $row['oracle_id']){
			$lv_aplcatns .= "<td>".$row['lv_tp']."</td>";
			$lv_aplcatns .= "<td>".$row['days_apld']."</td>";
			$lv_aplcatns .= "<td>".$row['apr_stat_dsc']."</td>";
			$lv_aplcatns .= "<td>".$row['dept']."</td>";
			$lv_aplcatns .= "<td>".$row['mstry']."</td>";
			if($row['apr_stat_dm'] == 0) $lv_aplcatns .= '<td><a href="'.WEB_ROOT.'/lv_process.php?lv_id='.$row['lv_id'].'&appr=3" class="failBtn">Cancel Leave</a></td>';
		}
		$lv_aplcatns .="</tr>";
	}
	$lv_aplcatns .= "</table>\n";
	echo "<h2>My leaves</h2>";
	echo $lv_aplcatns;
}else{
	if(isset($_POST['leaveApl']) && $_POST['leaveApl']=='Apply for Leave'){
		try{
			extract($_POST);
			$st_dt = date('Y-m-d',strtotime($st_dt)); $end_dt = date('Y-m-d',strtotime($end_dt));
			
			/// Update the email in the db
			$stmt_ins_apl = "INSERT INTO t_wb_lv_apl 
								(oracle_id, lv_tp, st_dt, end_dt, lv_rsn) 
								VALUES
								(:oracle_id, :lv_tp, :st_dt, :end_dt, :lv_rsn)";
			$stmt = $dbo->prepare($stmt_ins_apl);
			$stmt->execute( array( ':oracle_id' => @$_SESSION['oracle_id'], ':lv_tp' => $lv_tp
											, ':st_dt' => $st_dt, ':end_dt' => $end_dt, ':lv_rsn' => $lv_rsn)
								   );
			$ins_apl_id = $dbo->lastInsertId();
			//Prepare to notify 1st level supervisor
			$appr = $fxns->_getApprover($_SESSION['oracle_id']);
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			// Additional headers
			$headers .= 'From: Ministry of Science and Tech <no-reply@lasg.gov.ng>' . "\r\n";
			$headers .= 'Bcc:info@lasg.gov.ng' . "\r\n";
			
			extract($appr);
			$v_end_dt = strtotime($end_dt); $v_st_dt = strtotime($st_dt); $v_diff = ($v_end_dt - $v_st_dt)/60/60/24;
			$success_msg = $v_st_dt . ' '. $v_st_dt;			
			$leave_tp = $fxns->_getLOVDsc('t_wb_lv_tp_lov', $_POST['lv_tp']);
			$toMsg = "A new $leave_tp request for ".$v_diff." days has been made by <b>".$usr_nm."</b> in your department\r\n<br />";
			$toMsg .= "Reason: ".$_POST['lv_rsn']."<br /><br />";
			$toMsg .= "Click on the links below to either approve or reject<br /><br />";
			$toMsg .= '<div style="margin-left:150px;">';
			$toMsg .= '<a href="'.WEB_ROOT.'/lv_process.php?lv_id='.$ins_apl_id.'&usr='.$user_id.'&appr_lv=1&appr=1" style="background:#06F; padding:5px 10px; text-decoration:none;">Approve</a> &nbsp;&nbsp;&nbsp;&nbsp;';
			$toMsg .= '<a href="'.WEB_ROOT.'/lv_process.php?lv_id='.$ins_apl_id.'&usr='.$user_id.'&appr_lv=1&appr=0" style="background:#F60; padding:5px 10px; text-decoration:none;">Reject</a></div><br /><br />';
			$toMsg .= "You can also login to your account to either approve or reject the request";
			if(mail($appr_eml, "New Leave Request", $toMsg, $headers)) {
				$success_msg .= "Leave request sent.";
			} else {
				$success_msg .= "Your leave request has been made but a notification could not be sent to your supervisor<br />You may need to contact your supervisor in person and notify him/her of the request";
			}
		
		}catch (exception $e){
			$err_msg = "Error: ".$e->getMessage();
		}
	}
	require_once("assets/common/leave_apl_form.php");
}
/*
* Include the footer
*/
include_once 'assets/common/footer.inc.php';
?>
  <script type="text/javascript">$("select.lv_tp").change(function(){$(".leave_info").val( $(this).children(":selected").attr('data-rmks'));$('input.fromDate').attr('data-days', $(this).children(":selected").attr('data-days') )}).trigger('change')</script><!--//$(this).after( $(this).children(":selected").attr('data-rmks') ); -->
