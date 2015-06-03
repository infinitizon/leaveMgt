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
class Profile extends DB_Connect{
	/**
	* The eventual student as an array
	* @var string Stores the student as an array
	*/
	private $the_student;
	
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
	* Get all students registered in the database this session
	*
	* @return void
	*/
	public function _getAllProfiles()	{
		$get_profles = "SELECT a.r_k, a.oracle_id, a.fst_nm, a.mdl_nm, a.lst_nm, a.phn_no, a.eml_adr, a.pry_adr_ln1, a.pry_adr_ln2
							, a.pry_adr_city, a.pry_adr_sta, a.pry_adr_ctr, a.emp_tp
							, (SELECT b.val_dsc FROM t_wb_lov b WHERE b.val_id = a.dept AND b.def_id='00-DPT') dept
							, (SELECT c.val_dsc FROM t_wb_lov c WHERE c.val_id = a.mstry AND c.def_id='00-MIN') mstry
							, pass, apr_sta, active, crt_tm
						FROM t_wb_emp a";
		
		try{
			$stmt=$this->dbo->query($get_profles);
			$results = array();
			while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
				$results[]=$result;
			}
			return $results;
		}catch ( Exception $e ){
			return $e->getMessage() ;
		}
	}

	/**
	* Get detaqils of a particular student registered in the database based on the student_id passed
	*
	* @param int student_reg_id - an id representing a particular student;
	* @return void
	*/
	public function _getProfile($oracle_id)	{
		$get_emp_record = "SELECT a.r_k, a.oracle_id, a.fst_nm, a.mdl_nm, a.lst_nm, a.phn_no, a.eml_adr, a.pry_adr_ln1, a.pry_adr_ln2
							, a.pry_adr_city, a.pry_adr_sta, a.pry_adr_ctr, a.emp_tp
 							, dept dept_id
							, mstry mstry_id
 							, (SELECT b.val_dsc FROM t_wb_lov b WHERE b.val_id = a.dept AND b.def_id='00-DPT') dept
							, (SELECT c.val_dsc FROM t_wb_lov c WHERE c.val_id = a.mstry AND c.def_id='00-MIN') mstry
							, pass, apr_sta, active, crt_tm
						FROM t_wb_emp a
						WHERE oracle_id=:oracle_id";
		try{
			$stmt=$this->dbo->prepare($get_emp_record);
			$stmt->execute(array(':oracle_id'=>$oracle_id));
			$results = $stmt->fetch(PDO::FETCH_ASSOC);
			return $results;
		}catch ( Exception $e ){
			return $e->getMessage() ;
		}
	}

	/**
	* Get qualification details in the database based on the student_id passed
	*
	* @param int student_reg_id - an id representing a particular student;
	* @return array - An array of qualification details for the student
	*/
	public function _getStudQualificanDets($student_reg_id)	{
		$get_stud_record = "SELECT qld_det_id, student_reg_id, institution_type, institution_name
							, (CASE qlf_code
								WHEN 'FSLC' THEN 'First School Leaving Certificate'
								WHEN 'FDEG' THEN 'First Degree'
								ELSE qlf_code
							END)qlf_code
							, period_start, period_end		 
		FROM qualification_details
		WHERE `student_reg_id`=:student_reg_id";		
		try{
			$stmt=$this->dbo->prepare($get_stud_record);
			$stmt->execute(array(':student_reg_id'=>$student_reg_id));
			$results = array();
			while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
				$results[]=$result;
			}
			return $results;
		}catch ( Exception $e ){
			return $e->getMessage() ;
		}
	}

	/**
	* Get previous experience in the database based on the student_id passed
	*
	* @param int student_reg_id - an id representing a particular student;
	* @return array - An array of qualification details for the student
	*/
	public function _getStudPrevExpDets($student_reg_id)	{
		$get_stud_record = "SELECT getPrevExp(pre_type_id) AS pre_type_type , org_club_name, org_club_start_date, org_club_end_date, created 		 
							FROM previous_experience
							WHERE `student_reg_id`=:student_reg_id";		
		try{
			$stmt=$this->dbo->prepare($get_stud_record);
			$stmt->execute(array(':student_reg_id'=>$student_reg_id));
			$results = array();
			while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
				$results[]=$result;
			}
			return $results;
		}catch ( Exception $e ){
			return $e->getMessage() ;
		}
	}

	/**
	* Get any disabilities of the student in the database based on the student_id passed
	*
	* @param int student_reg_id - an id representing a particular student;
	* @return array - An array of disabilities for the student
	*/
	public function _getStudDisabilities($student_reg_id)	{
		$get_stud_record = "SELECT a.disability_name, b.disability_name AS chosen 
							FROM `disabilities` a
							LEFT JOIN 
								(SELECT getDisabilities(`disability_ids`) disability_name
								 FROM `disabilities_chosen` 
								 WHERE student_reg_id=:student_reg_id) b 
							ON a.disability_name = b.disability_name";		
		try{
			$stmt=$this->dbo->prepare($get_stud_record);
			$stmt->execute(array(':student_reg_id'=>$student_reg_id));
			$results = array();
			while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
				$results[]=$result;
			}
			return $results;
		}catch ( Exception $e ){
			return $e->getMessage() ;
		}
	}

	public function _getCurrSemesterCourses()	{
		$get_semester_courses = "SELECT c.id AS course_id, c.course_code, c.course_title, c.course_desc, c.course_unit, (SELECT id FROM registered_sem_courses WHERE `courses_id` = c.id AND student_reg_id={$_SESSION['student_reg_id']}) AS registered, (SELECT test1 FROM `registered_sem_courses` WHERE `courses_id` = c.id AND student_reg_id={$_SESSION['student_reg_id']}) AS test1, (SELECT test2 FROM `registered_sem_courses` WHERE `courses_id` = c.id AND student_reg_id={$_SESSION['student_reg_id']}) AS test2, (SELECT test3 FROM `registered_sem_courses` WHERE `courses_id` = c.id AND student_reg_id={$_SESSION['student_reg_id']}) AS test3, (SELECT exam FROM `registered_sem_courses` WHERE `courses_id` = c.id AND student_reg_id={$_SESSION['student_reg_id']}) AS exam, (SELECT score FROM `registered_sem_courses` WHERE `courses_id` = c.id AND student_reg_id={$_SESSION['student_reg_id']}) AS score, (SELECT grade FROM `registered_sem_courses` WHERE `courses_id` = c.id AND student_reg_id={$_SESSION['student_reg_id']}) AS grade, (SELECT date_completed FROM `registered_sem_courses` WHERE `courses_id` = c.id AND student_reg_id={$_SESSION['student_reg_id']}) AS date_completed FROM `course_semester_setup` AS s JOIN courses AS c ON s.courses_id=c.id WHERE s.semester =(SELECT curr_level FROM students_reg WHERE student_reg_id={$_SESSION['student_reg_id']}) AND courses_offered_id=(SELECT MAX(chosen_cat_id) FROM sports_of_study_chosen WHERE student_reg_id={$_SESSION['student_reg_id']})";
		
		try{
			$stmt=$this->dbo->query($get_semester_courses);
			$results = array();
			while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
				$results[]=$result;
			}
			return $results;
		}catch ( Exception $e ){
			return $e->getMessage() ;
		}
	}

	public function _getPastRegisteredCourses()	{
		$get_semester_courses = "SELECT c.course_code, c.course_title, c.course_desc, c.course_unit";
		$get_semester_courses .= ", (SELECT id FROM registered_sem_courses WHERE courses_code = c.course_code AND student_reg_id={$_SESSION['student_reg_id']}) AS registered";
		$get_semester_courses .= ", (SELECT test1 FROM `registered_sem_courses` WHERE `courses_code` = c.course_code AND student_reg_id={$_SESSION['student_reg_id']}) AS test1";
	$get_semester_courses .= ", (SELECT test2 FROM `registered_sem_courses` WHERE `courses_code` = c.course_code AND student_reg_id={$_SESSION['student_reg_id']}) AS test2";
	$get_semester_courses .= ", (SELECT test3 FROM `registered_sem_courses` WHERE `courses_code` = c.course_code AND student_reg_id={$_SESSION['student_reg_id']}) AS test3";
	$get_semester_courses .= ", (SELECT exam FROM `registered_sem_courses` WHERE `courses_code` = c.course_code AND student_reg_id={$_SESSION['student_reg_id']}) AS exam";
	$get_semester_courses .= ", (SELECT score FROM `registered_sem_courses` WHERE `courses_code` = c.course_code AND student_reg_id={$_SESSION['student_reg_id']}) AS score";
	$get_semester_courses .= ", (SELECT grade FROM `registered_sem_courses` WHERE `courses_code` = c.course_code AND student_reg_id={$_SESSION['student_reg_id']}) AS grade";
	$get_semester_courses .= ", (SELECT date_completed FROM `registered_sem_courses` WHERE `courses_code` = c.course_code AND student_reg_id={$_SESSION['student_reg_id']}) AS date_completed";
		$get_semester_courses .= " FROM `course_semester_setup` AS s";
		$get_semester_courses .= " JOIN courses AS c ON s.courses_id=c.id";
		$get_semester_courses .= " WHERE s.semester =(SELECT curr_level FROM students_reg WHERE student_reg_id={$_SESSION['student_reg_id']})";
$get_semester_courses .= " AND courses_offered_id=(SELECT MAX(chosen_cat_id) FROM sports_of_study_chosen WHERE student_reg_id={$_SESSION['student_reg_id']})";
		
		try{
			$stmt=$this->dbo->query($get_semester_courses);
			$results = array();
			while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
				$results[]=$result;
			}
			return $results;
		}catch ( Exception $e ){
			return $e->getMessage() ;
		}
	}
	/**
	* Delete a student based on the student id parameter passed
	*
	* @param int $student_reg_id a database object
	* @return void
	*/
	public function _deleteThisProfile($oracle_id)	{
		$del_prof_reg = "DELETE FROM t_wb_emp WHERE oracle_id=:oracle_id";
		try{
			$stmt=$this->dbo->prepare($del_prof_reg);
			$stmt->execute(array(':oracle_id' => $oracle_id));
		}catch(exception $e){
			return $e->getMessage();
		}
	}

}

?>