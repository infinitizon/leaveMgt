<?php
/**
* Builds the students
*
* PHP version 5
*
* LICENSE: This source file is subject to the MIT License, available at http://www.opensource.org/licenses/mit-license.html
*
* @author Abimbola Hassan <ahassan@infinitizon.com>
* @copyright 2012 infinitizon Design
* @license http://www.opensource.org/licenses/mit-license.html
*/
class Functions extends DB_Connect{
	/**
	* The eventual student as an array
	* @var string Stores the student as an array
	*/
	private $the_fxn;
	
	/**
	* Creates a database object and stores relevant data
	*
	* Upon instantiation, this class accepts a database object that, if not null, is stored in the object's private $_db
	* property. If null, a new PDO object is created and stored instead.
	*
	* @param object $dbo a database object
	* @return void
	*/
	public function __construct($dbo=NULL){
		/*
		 * Call the parent constructor to check for a database object
		*/
		parent::__construct($dbo);
	}
	
	/**
	* Get LOVs
	*
	* @return void
	*/
	public function _getLOVs($name, $def_id, $class_nm, $null_val=NULL, $curr_val = NULL)	{
		$get_profles = "SELECT val_id, val_dsc
						FROM t_wb_lov WHERE def_id='".$def_id."'";		
		try{
			$stmt=$this->dbo->query($get_profles);
			$this->the_fxn = "<select class='".$class_nm."' name='".$name."'>";
			$this->the_fxn .=($null_val != NULL ) ? "<option value=''>$null_val</option>" : '';
			while ($items = $stmt->fetch()){
				extract($items);
				$this->the_fxn .= "<option value='".$val_id."'";
				$this->the_fxn .=($curr_val != NULL && $curr_val==$val_id) ? ' selected="selected" ' : '';
				$this->the_fxn .=">".$val_dsc."</option>";
			}
			$this->the_fxn .= "</select>";
			return $this->the_fxn;
		}catch ( Exception $e ){
			return $e->getMessage() ;
		}
	}
	
	/**
	* Get LOVs
	*
	* @return void
	*/
	public function _getleaveTypes($class_nm, $curr_val = NULL)	{
		$get_profles = "SELECT val_id, val_dsc, lv_days, rmks
						FROM t_wb_lv_tp_lov";
		$get_profles .= isset($curr_val) ? "WHERE lv_tp_id='".$curr_val."'" : '';		
		try{
			$stmt=$this->dbo->query($get_profles);
			$this->the_fxn = "<select class='".$class_nm."' name='lv_tp'>";
			while ($items = $stmt->fetch()){
				extract($items);
				$this->the_fxn .= "<option value='".$val_id."'";
				$this->the_fxn .=($curr_val != NULL && curr_val==$val_id) ? ' selected="selected" ' : '';
				$this->the_fxn .=' data-rmks="'.$lv_days.' days - '.$rmks.'" data-days="'.$lv_days.'">'.$val_dsc."</option>";
			}
			$this->the_fxn .= "</select>";
			return $this->the_fxn;
		}catch ( Exception $e ){
			return $e->getMessage() ;
		}
	}
	/**
	* Get LOV Description
	*
	* @return void
	*/
	public function _getLOVDsc($table_nm, $val_id, $def_id = NULL)	{
		$get_LOVDsc = "SELECT val_dsc
						FROM $table_nm WHERE val_id=$val_id";
		$get_LOVDsc .= ($def_id != NULL )? " AND def_id='".$def_id."'" : "";
		try{ 
			$stmt=$this->dbo->query($get_LOVDsc);
			while ($items = $stmt->fetch()){
				$this->the_fxn = $items['val_dsc'];
			}
			return $this->the_fxn;
		}catch ( Exception $e ){
			return $e->getMessage() ;
		}
	}
	/**
	* Get email Approver
	*
	* @return String
	*/
	public function _getApprover($oracle_id, $sup_lvl=1)	{
		$get_sup_lvl = "SELECT $oracle_id user_id
						, (SELECT CONCAT_WS(' ',lst_nm,fst_nm) FROM t_wb_emp WHERE oracle_id = $oracle_id) usr_nm
						, (SELECT b.eml_adr FROM t_wb_emp b WHERE oracle_id = a.oracle_id) appr_eml 
						FROM t_wb_appr a
							WHERE a.dept_id = (SELECT dept FROM t_wb_emp WHERE oracle_id = $oracle_id)
							AND a.mnst_id = (SELECT mstry FROM t_wb_emp WHERE oracle_id = $oracle_id)
							AND a.appr_lvl = $sup_lvl";
		try{
			$stmt=$this->dbo->query($get_sup_lvl);
			while ($items = $stmt->fetch(PDO::FETCH_ASSOC)){
				$this->the_fxn = $items;
			}
			return $this->the_fxn;
		}catch ( Exception $e ){
			return $e->getMessage() ;
		}
	}
	/**
	* Count number of approvers
	*
	* @return String
	*/
	public function _getThisLeave($lv_id)	{
		$get_sup_lvl = "SELECT r_k, oracle_id, lv_tp, lv_rsn, apr_stat, st_dt, end_dt,  DATEDIFF( end_dt, st_dt ) days_apld, crt_dt 
						FROM t_wb_lv_apl
							WHERE r_k='".$lv_id."'";
		try{
			$stmt=$this->dbo->query($get_sup_lvl);
			while ($items = $stmt->fetch(PDO::FETCH_ASSOC)){
				$this->the_fxn = $items;
			}
			return $this->the_fxn;
		}catch ( Exception $e ){
			return $e->getMessage() ;
		}
	}
	/**
	* Count number of approvers
	*
	* @return String
	*/
	public function _getApprCnt($oracle_id)	{
		$get_sup_lvl = "SELECT count(*) count 
						FROM t_wb_appr a
							WHERE a.dept_id = (SELECT dept FROM t_wb_emp WHERE oracle_id = $oracle_id)
							AND a.mnst_id = (SELECT mstry FROM t_wb_emp WHERE oracle_id = $oracle_id)";
		try{
			$stmt=$this->dbo->query($get_sup_lvl);
			while ($items = $stmt->fetch(PDO::FETCH_ASSOC)){
				$this->the_fxn = $items['count'];
			}
			return $this->the_fxn;
		}catch ( Exception $e ){
			return $e->getMessage() ;
		}
	}
	/**
	* Get existing leaves
	*
	* @return String
	*/
	public function _getleaveReqs($apr_stat = NULL, $include_me = false)	{
		$get_sup_lvl = "SELECT a.oracle_id, a.fst_nm, a.lst_nm, b.r_k lv_id, DATEDIFF( b.end_dt, b.st_dt ) days_apld, b.apr_stat apr_stat_dm
							, (SELECT val_dsc FROM t_wb_lov WHERE val_id=b.apr_stat AND def_id='00-STAT') apr_stat_dsc
							, (SELECT val_dsc FROM t_wb_lv_tp_lov WHERE val_id=b.lv_tp) lv_tp 
							, (SELECT val_dsc FROM t_wb_lov WHERE val_id=a.dept AND def_id='00-DPT') dept 
							, (SELECT val_dsc FROM t_wb_lov WHERE val_id=a.mstry AND def_id='00-MIN') mstry,
						   (SELECT oracle_id 
							FROM t_wb_appr c
							WHERE c.dept_id = (SELECT dept FROM t_wb_emp WHERE oracle_id = a.oracle_id)
								AND c.mnst_id = (SELECT mstry FROM t_wb_emp WHERE oracle_id = a.oracle_id)
								AND c.appr_lvl = 1) sup1,
							(SELECT oracle_id 
								FROM t_wb_appr c
								WHERE c.dept_id = (SELECT dept FROM t_wb_emp WHERE oracle_id = a.oracle_id)
									AND c.mnst_id = (SELECT mstry FROM t_wb_emp WHERE oracle_id = a.oracle_id)
									AND c.appr_lvl = 2) sup2
						FROM t_wb_emp a
						JOIN t_wb_lv_apl b
						ON a.oracle_id = b.oracle_id";
		if(!$include_me) "WHERE a.oracle_id<>".$_SESSION['oracle_id'];
		if ($apr_stat != NULL) $get_sup_lvl.=" AND apr_stat='".$apr_stat."'";
		try{
			$stmt=$this->dbo->query($get_sup_lvl);
			$results = array();
			while( $result = $stmt->fetch(PDO::FETCH_ASSOC) ){
				$results[]=$result;
			}
			return $results;
		}catch ( Exception $e ){
			return $e->getMessage() ;
		}
	}


}

?>