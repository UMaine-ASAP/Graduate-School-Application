<?php
	include_once "database.php";
	
	$db = new Database();
	$db->connect;
	
	$results = "";
	if($_POST['type'] == "dept") {
		$programs = $db->query("SELECT DISTINCT academic_dept_heading FROM um_academic ORDER BY academic_dept_heading ASC");
		foreach($programs as $program){
			//$results .= '<option value="'.$program['academic_dept_code'].'">'.$program['academic_dept'].'</option>,';
			$results .= $program['academic_dept_heading']."*";
		}
		
	} elseif ($_POST['type'] == "degree") {
		if($_POST['dept']){
			$programs = $db->query("SELECT academic_program, academic_dept_code, academic_degree_heading FROM um_academic WHERE academic_dept_heading='%s'", $_POST['dept']);
			foreach($programs as $program){
				$results .= $program['academic_program']."_".$program['academic_degree_heading']."*";

				//$results .= '<option value="'.$program['academic_program'].'">'.$program['description_app'].'</option>';
			}
		} else {
			$programs = $db->query("SELECT academic_degree_heading,academic_dept_heading,academic_program FROM um_academic ORDER BY academic_dept_heading ASC");
			foreach($programs as $program){
				$results .= $program['academic_program']."_".$program['academic_dept_heading']." ".$program['academic_degree_heading']."*";	
				//$results .= '<option value="'.$program['academic_program'].'">'.$program['description_app'].'</option>';
			}
		}
	}

	$db->close();

	$results = substr($results,0,results.length-1);
	print $results;
	
?>
