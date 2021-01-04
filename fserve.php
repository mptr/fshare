<?php
$_GET['sname'] = preg_replace('/[^a-zA-Z0-9ÜÖÄüöä]/', "", $_GET['sname']);
if(!isset($_GET['pw'])) {
	$_GET['pw'] = "";
}
if (isset($_GET["zip"])) {
	pwcheck();
	// check for changes
	$md5all = "";
	$filesIn = glob("shares/" . $_GET['sname'] . "/*");
	foreach ($filesIn as $f) {
		$md5all .= md5_file($f);
	}
	$md5total = md5($md5all);
	$zip_file = "tmp/" . $md5total . "_" . $_GET['sname'] . ".zip";
	// isZip aktuell?
	if(!file_exists("tmp/" . $md5total . "_" . $_GET['sname'] . ".zip")) {
		set_time_limit(60*5);
		// reproc if naktuell
		$dir = 'shares/' . $_GET['sname'];
		$rootPath = realpath($dir);
		$zip = new ZipArchive();
		$zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);
		$files = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($rootPath),
			RecursiveIteratorIterator::LEAVES_ONLY
		);
		foreach ($files as $name => $file) {
			if (!$file->isDir()) {
				$filePath = $file->getRealPath();
				$relativePath = substr($filePath, strlen($rootPath) + 1);
				if($relativePath[0] != "_") {
					$zip->addFile($filePath, $relativePath);
				}
			}
		}
		$zip->close();
	}
	// serve old or new zip
	setcookie("downloadDone","true");
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: filename="' . $_GET['sname'] . '.zip"');
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize($zip_file));
	readfile($zip_file);
	die();
}
$_GET['fname'] = preg_replace('/[^\.a-zA-Z0-9-_ÜÖÄüöä]/', "", $_GET['fname']);
$_GET['fname'] = preg_replace('/\.{1,}/', ".", $_GET['fname']); // remove multiple . to single . (prevents dir-up)
if (!isset($_GET['fname']) || !isset($_GET['sname']) || !isset($_GET['pw'])) {
	http_response_code(404);
	die();
}
pwcheck();
// fcheck
if (!file_exists("shares/" . $_GET['sname'] . "/" . $_GET['fname'])) {
	http_response_code(404);
	die();
}
// actual serve
$filename = "shares/" . $_GET['sname'] . "/" . $_GET['fname'];
$fi = pathinfo($filename);
// dedice prev type
if (isset($_GET['preview'])) {
	$fi['extension'] = strtolower($fi['extension']);
	switch ($fi['extension']) {
		case 'jpg':
		case 'jpeg':
		case 'png':
			header('Content-Type: image/jpeg');
			$source;
			if ($fi['extension'] == "png") {
				$source = imagecreatefrompng($filename);
			} else {
				$source = imagecreatefromjpeg($filename);
			}
			$thumb = imagescale($source, 800);
			imagejpeg($thumb);
			break;
		default:
			echoFileIco($fi['extension']);
			break;
	}
} else {
	echoImgFromFname($filename);
}
function echoFileIco($ext) {
	include("fTypeColor.php");
	$c = fileTypeGetColor($ext);
	ob_start();
	include('fileico.php');
	$output = ob_get_contents();
	ob_end_clean();
	die($output);
}
function echoImgFromFname($fname)
{
	$cType = mime_content_type("/var/www/html/fshare.m-ptr.de/" . $fname);
	$fp = fopen($fname, 'rb');
	$pi = pathinfo($fname);
	header('Content-Disposition: filename="' . $pi['basename'] . '"');
	if ($cType && $fp) {
		header('Content-Type: ' . $cType);
		header('Content-Length: ' . filesize($fname));
		fpassthru($fp);
		exit;
	} else if ($fp) {
		header('Content-Length: ' . filesize($fname));
		fpassthru($fp);
	} else {
		http_response_code(505);
	}
}
function pwcheck() {
	if (file_exists("/var/www/html/fshare.m-ptr.de/shares/" . $_GET['sname'] . "/_spasswd")) {
		if ($_GET['pw'] != file_get_contents("/var/www/html/fshare.m-ptr.de/shares/" . $_GET['sname'] . "/_spasswd")) {
			http_response_code(403);
			die();
		}
	}
}