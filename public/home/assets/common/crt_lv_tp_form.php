<form id="updt_pass_form" name="updt_pass_form" method="post" action="<?php echo WEB_ROOT; ?>/home/crt_lv_tp.php">
   <table width="453" border="0">
   <tr>
      <td colspan="2">
			<?php 
				echo isset($err_msg) ? "<div class='err'>".$err_msg.'</div>' : '';
				echo isset($success_msg) ? "<div class='success'>".$success_msg.'</div>' : '';
			?></td>
     </tr>
   <tr>
      <td width="150" class="align_txt_r">Leave name:</td><td><input type="text" name="val_dsc" value="<?php echo @$oracle_id; ?>" /></td>
   </tr>
   <tr>
      <td class="align_txt_r">Leave days:</td><td width="152"><input type="text" name="lv_days"  value="<?php echo @$oracle_id; ?>"/></td>
   </tr>
      <tr>
      <td class="align_txt_r">Remarks:</td><td><textarea name="rmks"><?php echo @$oracle_id; ?></textarea></td>
   </tr>
   <tr>
      <td>&nbsp;</td>
      <td><input name="cr8_lv" id="cr8_lv" class="button" type="submit" value="Create Leave" /></td>
   </tr>
   </table>
</form>
