<?php

	//Paramters are filenames to merge excluding the last which is the output filename
	if( !isset($argv[1]) ) exit(0);

	include_once 'lib/PDFMerger/PDFMerger.php';

	array_shift($argv); //remove first argument
	$pdf = new PDFMerger;

	while( count($argv) > 1) {
		$file = array_shift($argv);
		$pdf->addPDF($file, 'all');
	}

	//Output pdf
	$output_name = array_shift($argv);
	$pdf->merge('file', $output_name);

?>