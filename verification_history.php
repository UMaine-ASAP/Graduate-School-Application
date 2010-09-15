<?php
if($_POST) {
	echo "post was TRUE\n<br />";
	$prev = $_POST['prev_um_app'];
	$discip = $_POST['disciplinary_violations'];
	$criminal = $_POST['criminal_violation']
	$GRE_verbal = $_POST['gre_verbal'];
	$GRE_quantitative = $_POST['gre_quantitative'];
	$GRE_analytical = $_POST['gre_analytical'];
	$GMAT_score = $_POST['gmat_score'];
	$MAT_score = $_POST['mat_score'];
	$pattern = "(^([1-9]{0,1})([1-9]{0,1})([0-9]{1})(\.[0-9])?$)";
	$replacement = "";
	$GRE_verb = preg_replace($pattern, $replacement, $GRE_verbal);
	$GRE_quant = preg_replace($pattern, $replacement, $GRE_quantitative);
	$GRE_analyt = preg_replace($pattern, $replacement, $GRE_analytical);
	$GMAT_Score = preg_replace($pattern, $replacement, $GMAT_score);
	$MAT_Score = preg_replace($pattern, $replacement, $MAT_score);

	if (empty($prev) || (empty($school_name) || (empty($school_city) || (empty($school_country) || (empty($major) || (empty($degree) || (empty($discip) \
		($GRE_verb != '') || ($GRE_analyt != '') || ($GRE_quantitative != '') || ($GMAT_Score != '') || ($MAT_Score != '')) {
					
					// errors
					if (empty($prev) echo "Have you previously applied to the University of Maine was left blank<br />";
					if (empty($discip) echo "Disciplinary radio buttons was left blank<br />";
					if ($GRE_verb != '') echo "Grades need to be numberical only<br />";
					if ($GRE_analyt != '') echo "Grades need to be numberical only<br />";
					if ($GRE_quant != '') echo "Grades need to be numberical only<br />";
					if ($GMAT_Score != '') echo "Grades need to be numberical only<br />";
					if ($MAT_Score != '') echo "Grades need to be numberical only<br />";
					
	}
} else { echo "no post"; }
?>
