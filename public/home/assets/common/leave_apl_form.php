<?php
if(@$_GET['view']){
?>
<form action="" method="post" enctype="application/x-www-form-urlencoded" name="v_leave">
<table>
  <tr>
    <td>&nbsp;</td>
    <td class="float_right"><a href="<?php WEB_ROOT ?>/home/leavemgt.php?sn=1" class="button">Cancel</a></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>Status</td>
    <td><input type="text" name="apr_stat" readonly="readonly" value="<?php echo $fxns->_getLOVDsc('t_wb_lov', $dsLeave['apr_stat'], '00-STAT'); ?>" /></td>
  </tr>
  <tr>
    <td>Requester</td>
    <?php $r_prof_det = $profile->_getProfile($dsLeave['oracle_id']); ?>
    <td><input type="text" name="oracle_id" readonly="readonly" value="<?php echo $r_prof_det['fst_nm'].' '.$r_prof_det['mdl_nm'].' '.$r_prof_det['lst_nm'] ?>" /></td>
  </tr>
  <tr>
    <td>Leave type</td>
    <td><input type="text" name="lv_tp" readonly="readonly" value="<?php echo $fxns->_getLOVDsc('t_wb_lv_tp_lov', $dsLeave['lv_tp']); ?>" /></td>
  </tr>
  <tr>
    <td>Reason</td>
    <td><textarea name="lv_rsn"><?php echo $dsLeave['lv_rsn'] ?></textarea></td>
  </tr>
  <tr>
    <td>Start Date</td>
    <td><input type="text" name="st_dt" readonly="readonly" value="<?php echo $dsLeave['st_dt'] ?>" /></td>
  </tr>
  <tr>
    <td>End Date</td>
    <td><input type="text" name="end_dt" readonly="readonly" value="<?php echo $dsLeave['end_dt'] ?>" /></td>
  </tr>
</table>

</form>
<?php
}else{?>
<form id="updt_pass_form" name="updt_pass_form" method="post" action="<?php echo WEB_ROOT; ?>/home/leavemgt.php">
    <h2>Apply for leave</h2>
    <table border="0">
       <tr>
          <td colspan="2">
			<?php if($_SESSION['emp_tp'] > 1){ ?>
                <a href="<?php echo WEB_ROOT ?>/home/leavemgt.php?sn=1" class="button" style="float:right;">Sanction leave</a>
            <?php } ?>
          </td>
       </tr>
       <tr>
          <td colspan="2">
                <?php 
                    echo isset($err_msg) ? "<div class='err'>".$err_msg.'</div>' : '';
                    echo isset($success_msg) ? "<div class='success'>".$success_msg.'</div>' : '';
                ?></td>
       </tr>
      <tr>
        <td class="float_right">Oracle ID.:</td>
        <td class="emphasis"><?php echo $_SESSION['oracle_id']; ?></a></td>
      </tr>
      <tr>
        <td class="float_right">Leave Type:</td>
      <td><?php echo $fxns->_getleaveTypes('lv_tp'); ?></td>
      </tr>
      <tr>
        <td class="float_right">Information:</td>
        <td class="emphasis"><input type="text" name="leave_info" class="leave_info" readonly="readonly" size="40" /></td>
      </tr>
      <tr>
        <td class="float_right">Leave Start Date:</td>
        <td class="emphasis"><input type="text" name="st_dt" class="fromDate" /></td>
      </tr>
      <tr>
        <td class="float_right">Leave End Date:</td>
        <td class="emphasis"><input type="text" name="end_dt" class="toDate" /></td>
      </tr>
      <tr>
        <td class="float_right">Reason for leave:</td>
        <td class="emphasis"><textarea name="lv_rsn" class="lv_rsn"><?php echo @$leave_rsn; ?></textarea></td>
      </tr>
      <tr>
        <td class="float_right">&nbsp;</td>
        <td class="emphasis"><input type="submit" name="leaveApl" class="button" value="Apply for Leave" /></td>
      </tr>
    </table>
</form>
<?php } ?>