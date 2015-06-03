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
$font_awesome_files = array('font-awesome.css', 'prettify.css');
$general_js_files = array('jquery-1.7.2.min.js', 'jquery-ui-1.8.21.custom.min.js', 'slides.min.jquery.js');
$page_js_files = array('general.js', 'common.js', 'inline_edit.js', 'jqueryui-editable.min.js');
/*
* Include the header
*/
include_once 'assets/common/header.inc.php';
$profile = new Profile($dbo);
$prof_det = $profile->_getProfile(@$_SESSION['oracle_id']);
?>
<h2>Personal details</h2>
<span class="important">Important Notice</span>
<ol>
	<li>You can edit the editable fields by simply clicking on field.</li><li>Once editing is complete, hit ENTER to save</li>
</ol>
<table border="0">
  <tr>
    <td class="float_right">Surname:</td>
    <td class="emphasis"><?php echo $prof_det['lst_nm']; ?></a></td>
  </tr>
  <tr>
    <td class="float_right">First Name:</td>
    <td class="emphasis"><?php echo $prof_det['mdl_nm']; ?></td>
  </tr>
  <tr>
    <td class="float_right">Other Names:</td>
    <td class="emphasis"><?php echo $prof_det['fst_nm']; ?></td>
  </tr>
  <tr>
    <td class="float_right">Email Address:</td>
    <td class="emphasis"><a href="javascript:;" class="txtEdit" id="eml_adr" data-pk="<?php echo $_SESSION['oracle_id']; ?>"><?php echo $prof_det['eml_adr']; ?></a></td>
  </tr>
  <tr>
    <td class="float_right">Residential Address 1:</td>
    <td class="emphasis"><a href="javascript:;" class="txtAreaEdit" id="pry_adr_ln1" data-pk="<?php echo $_SESSION['oracle_id'];?>"><?php echo $prof_det['pry_adr_ln1']; ?></a></td>
  </tr>
  <tr>
    <td class="float_right">Residential Address 2:</td>
    <td class="emphasis"><a href="javascript:;" class="txtAreaEdit" id="pry_adr_ln2" data-pk="<?php echo $_SESSION['oracle_id']; ?>"><?php echo $prof_det['pry_adr_ln2']; ?></a></td>
  </tr>
  <tr>
    <td class="float_right">Residential City:</td>
    <td class="emphasis"><a href="javascript:;" class="txtEdit" id="pry_adr_city" data-pk="<?php echo $_SESSION['oracle_id']; ?>"><?php echo $prof_det['pry_adr_city']; ?></a></td>
  </tr>
  <tr>
    <td class="float_right">Residential State:</td>
    <td class="emphasis"><a href="javascript:;" class="txtEdit" id="pry_adr_sta" data-pk="<?php echo $_SESSION['oracle_id']; ?>"><?php echo $prof_det['pry_adr_sta']; ?></a></td>
  </tr>
  <tr>
    <td class="float_right">Residential Country:</td>
    <td class="emphasis"><a href="javascript:;" class="txtEdit" id="pry_adr_ctr" data-pk="<?php echo $_SESSION['oracle_id']; ?>"><?php echo $prof_det['pry_adr_ctr']; ?></a></td>
  </tr>
  <tr>
    <td class="float_right">Ministry:</td>
    <td class="emphasis"><?php echo $prof_det['mstry']; ?></td>
  </tr>
</table>
<br /><br />
<?php
/*
* Include the footer
*/
include_once 'assets/common/footer.inc.php';
?>