<?php
function fileTypeGetColor($ext) {
	$c = "2e2e2e";
	switch ($ext) {
		case 'xls':
		case 'xlsx':
			$c = "217346";
			break;
		case 'ppt':
		case 'pptx':
		case 'ppsx':
			$c = "B7472A";
			break;
		case 'doc':
		case 'docx':
			$c = "2B579A";
			break;
		case 'txt':
			$c = "95A5A5";
			break;
		case 'stl':
			$c = "FF5364";
			break;
		case 'pdf':
			$c = "AB0707";
			break;
		case 'php':
			$c = "E96360";
			break;
		case 'css':
			$c = "0096E6";
			break;
		case 'mp4':
			$c = "FF5364";
			break;
		case 'html':
			$c = "EC6630";
			break;
		case 'svg':
			$c = "E57E25";
			break;
		case 'zip':
		case 'gz':
		case 'tar':
			$c = "556080";
			break;
		case 'arw':
			$c = "9777A8";
			break;
		case 'js':
			$c = "EEAF4B";
			break;
		default:
			$c = "555555";
			break;
	}
	return $c;
}
?>
