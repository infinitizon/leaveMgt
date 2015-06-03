<?php
/*
* Include necessary files
*/
include_once 'core/init.inc.php';
/*
* Set up the page title and CSS files
*/
$page_title = ":: Create Employee &rsaquo;&rsaquo; Leave Management System ::";
$common_css_files = array('jquery-ui-1.8.21.custom.css','common.css');
$page_css_files = array('general.css', 'jqueryui-editable.css');
$font_awesome_files = array('font-awesome.css', 'prettify.css');
$general_js_files = array('jquery-1.7.2.min.js', 'jquery-ui-1.8.21.custom.min.js');
$page_js_files = array('common.js', 'inline_edit.js', 'jqueryui-editable.min.js');
/*
* Include the header
*/
include_once 'assets/common/header.inc.php';
$fxns = new Functions($dbo);
?>
<?php

if(!isset($_GET['add'])){
	$profile = new Profile($dbo);

	if(@$_GET['edit'] && !empty($_GET['edit'])){
		$prof_det = $profile->_getProfile(@$_GET['edit']);
?>
<table border="0">
  <tr>
    <td class="align_txt_r" colspan="2"><a href="<?php echo WEB_ROOT; ?>/home/crt_emp.php" class="button">Cancel</a></td>
  </tr>
  <tr>
    <td class="float_right" colspan="2"><h2>Personal details</h2>
<span class="important">Important Notice</span>
<ol>
	<li>You can edit the editable fields by simply clicking on field.</li><li>Once editing is complete, hit ENTER to save</li>
</ol>
</td>
  </tr>
  <tr>
    <td class="float_right">Surname:</td>
    <td class="emphasis"><a href="javascript:;" class="txtEdit" id="lst_nm" data-pk="<?php echo $_GET['edit']; ?>"><?php echo $prof_det['lst_nm']; ?></a></td>
  </tr>
  <tr>
    <td class="float_right">First Name:</td>
    <td class="emphasis"><a href="javascript:;" class="txtEdit" id="fst_nm" data-pk="<?php echo $_GET['edit']; ?>"><?php echo $prof_det['fst_nm']; ?></a></td>
  </tr>
  <tr>
    <td class="float_right">Other Names:</td>
    <td class="emphasis"><a href="javascript:;" class="txtEdit" id="mdl_nm" data-pk="<?php echo $_GET['edit']; ?>"><?php echo $prof_det['mdl_nm']; ?></a></td>
  </tr>
  <tr>
    <td class="float_right">Email Address:</td>
    <td class="emphasis"><a href="javascript:;" class="txtEdit" id="eml_adr" data-pk="<?php echo $_GET['edit']; ?>"><?php echo $prof_det['eml_adr']; ?></a></td>
  </tr>
  <tr>
    <td class="float_right">Residential Address 1:</td>
    <td class="emphasis"><a href="javascript:;" class="txtAreaEdit" id="pry_adr_ln1" data-pk="<?php echo $_GET['edit'];?>"><?php echo $prof_det['pry_adr_ln1']; ?></a></td>
  </tr>
  <tr>
    <td class="float_right">Residential Address 2:</td>
    <td class="emphasis"><a href="javascript:;" class="txtAreaEdit" id="pry_adr_ln2" data-pk="<?php echo $_GET['edit']; ?>"><?php echo $prof_det['pry_adr_ln2']; ?></a></td>
  </tr>
  <tr>
    <td class="float_right">Residential City:</td>
    <td class="emphasis"><a href="javascript:;" class="txtEdit" id="pry_adr_city" data-pk="<?php echo $_GET['edit']; ?>"><?php echo $prof_det['pry_adr_city']; ?></a></td>
  </tr>
  <tr>
    <td class="float_right">Residential State:</td>
    <td class="emphasis"><a href="javascript:;" class="txtEdit" id="pry_adr_sta" data-pk="<?php echo $_GET['edit']; ?>"><?php echo $prof_det['pry_adr_sta']; ?></a></td>
  </tr>
  <tr>
    <td class="float_right">Residential Country:</td>
    <td class="emphasis"><a href="javascript:;" class="txtEdit" id="pry_adr_ctr" data-pk="<?php echo $_GET['edit']; ?>"><?php echo $prof_det['pry_adr_ctr']; ?></a></td>
  </tr>
  <tr>
    <td class="float_right">Ministry:</td>
    <td class="emphasis"><a href="javascript:;" class="selectEdit" id="mstry" data-pk="<?php echo $_GET['edit']; ?>"><?php echo $prof_det['pry_adr_ctr']; ?></a></td>
  </tr>
</table>
<br /><br />
<?php
	}else{
		//Landing page view all records or already performed delete operation
		if(@$_GET['del'] && !empty($_GET['del'])){
			$del_prof = $profile->_deleteThisProfile($_GET['del']);
		}
		$prof_det = $profile->_getAllProfiles();
		$all_prof = "<table border='1' rules='all' class='my_tables clickable'>\n";
		$all_prof .= '<td colspan="7" class="align_txt_r"><a href="'.WEB_ROOT."/home/crt_emp.php?add=1".'" class="button">Add New Record</a></td>';
		$all_prof .= "<tr><th>Oracle Id</th><th>Name</th><th>Phone</th><th>Email</th><th>Address</th><th colspan='2'>&nbsp;</th></tr>";
		foreach($prof_det as $index => $row){
			$all_prof .= "<tr>";
			$all_prof .= "<td>".$row['oracle_id']."</td>";
			$all_prof .= "<td>".$row['fst_nm'].' '.$row['mdl_nm'].' '.$row['lst_nm']."</td>";
			$all_prof .= "<td>".$row['phn_no']."</td>";
			$all_prof .= "<td>".$row['eml_adr']."</td>";
			$all_prof .= "<td>".$row['pry_adr_ln1'].$row['pry_adr_ln1'].$row['pry_adr_ln1']."</td>";
			$all_prof .= "<td><a href='".WEB_ROOT."/home/crt_emp.php?edit=".$row['oracle_id']."' style='color:#00F;'>Edit</a></td>";
			$all_prof .= "<td><a href='".WEB_ROOT."/home/crt_emp.php?del=".$row['oracle_id']."' class='delete' style='color:#F00;' data='Are you sure you want to delete this profile and all associated data?'>Delete</a></td>";
			$all_prof .= "</tr>\n";
		}
		$all_prof .= "</table>\n";
		echo $all_prof;
	}
}else{
	if(isset($_POST['crt_emp_rec']) && $_POST['crt_emp_rec']=='Create Employee Record'){
		$stmt_chk_user = "SELECT oracle_id FROM t_wb_emp WHERE oracle_id=:oracle_id";
		$stmt=$dbo->prepare($stmt_chk_user);
		$stmt->execute( array(':oracle_id' => $_POST['oracle_id']) );
			
		if($stmt->rowCount()){	//Check if a record is found.
			extract($_POST);
			$err_msg = "The user with oracle id '".$_POST['oracle_id']."' already exists";
		}else{
			try{
				/// Update the email in the db
				$stmt_ins_usr = "INSERT INTO t_wb_emp 
									(oracle_id, fst_nm, mdl_nm, lst_nm, phn_no, eml_adr, pry_adr_ln1, pry_adr_ln2, pry_adr_city
									 , pry_adr_sta, pry_adr_ctr, emp_tp, pass, apr_sta, active) 
									VALUES
									(:oracle_id, :fst_nm, :mdl_nm, :lst_nm, :phn_no, :eml_adr, :pry_adr_ln1, :pry_adr_ln2, :pry_adr_city
									 , :pry_adr_sta, :pry_adr_ctr, :emp_tp, :pass, :apr_sta, :active)";
				$stmt_ins_usr = $dbo->prepare($stmt_ins_usr);
				$stmt_ins_usr->execute( array( ':oracle_id' => $_POST['oracle_id'], ':fst_nm' => $_POST['fst_nm']
												, ':mdl_nm' => $_POST['mdl_nm'], ':lst_nm' => $_POST['lst_nm']
												, ':phn_no' => $_POST['phn_no'], ':eml_adr' => $_POST['eml_adr']
												, ':pry_adr_ln1' => $_POST['pry_adr_ln1'], ':pry_adr_ln2' => $_POST['pry_adr_ln2']
												, ':pry_adr_city' => $_POST['pry_adr_city'], ':pry_adr_sta' => $_POST['pry_adr_sta']
												, ':pry_adr_ctr' => $_POST['pry_adr_ctr'], ':fst_nm' => $_POST['pry_adr_ctr']
												, ':emp_tp' => $_POST['emp_tp'], ':pass' => sha1( $salt . md5($_POST['lst_nm'] . $salt))
												, ':apr_sta' => isset($_POST['apr_sta'])?$_POST['apr_sta']:0, ':active' => isset($_POST['active'])?$_POST['active']:0)
									   );
				$success_msg = "User created successful.";
	
			}catch (exception $e){
				$err_msg = "Error: ".$e->getMessage();
			}
		}
	}
	require_once("assets/common/crt_emp_form.php");
}
?>

<?php
/*
* Include the footer
*/
include_once 'assets/common/footer.inc.php';
?>