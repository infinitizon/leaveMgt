<?php
/*
* Include necessary files
*/
include_once 'core/init.inc.php';
/*
* Set up the page title and CSS files
*/
$page_title = ":: Home &rsaquo;&rsaquo; Leave management system ::";
$common_css_files = array('jquery-ui-1.8.21.custom.css', 'common.css');
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
if(isset($_POST['cr8_lv']) && $_POST['cr8_lv']=='Create Leave'){
	$stmt_chk_lv = "SELECT val_dsc FROM t_wb_lv_tp_lov WHERE val_dsc=:val_dsc";
	$stmt_chk=$dbo->prepare($stmt_chk_lv);
	$stmt_chk->execute(array(':val_dsc' => trim($_POST['val_dsc'])));
	if($stmt_chk->rowCount()){	//Check if a record is found.
		extract($_POST);
		$err_msg = "Leave type ".$_POST['val_dsc']." already exists";
	}else{
		try{
			$stmt_ins_lv_tp = "INSERT INTO t_wb_lv_tp_lov (val_dsc, lv_days, rmks)";
			$stmt_ins_lv_tp .= "VALUES ";
			$stmt_ins_lv_tp .= "(:val_dsc, :lv_days, :rmks)";
			$stmt_ins = $dbo->prepare($stmt_ins_lv_tp);
			$stmt_ins->execute(array(':val_dsc' => $_POST['val_dsc'], ':lv_days' =>$_POST['lv_days'], ':rmks' => $_POST['rmks']));
			$success_msg = "New leave type ".$_POST['val_dsc']." created successfully";
		}catch (exception $e){
			$err_msg = "Error: ".$e->getMessage();
		}
	}
	$message = isset($success_msg) ? $success_msg : $err_msg;
	$type = isset($success_msg) ? 'success' : 'err';
}
require_once("assets/common/crt_lv_tp_form.php");
?>
</div><!-- End content  -->
<?php
/*
* Include the footer
*/
include_once 'assets/common/footer.inc.php';
?>