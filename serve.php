<?php 
if(isset($_POST['pw'])) {
    setcookie("sharePW".$_GET['n'],$_POST['pw']);
} else if(isset($_COOKIE["sharePW".$_GET['n']])) {
    $_POST['pw'] = $_COOKIE["sharePW".$_GET['n']];
}?>
<!DOCTYPE HTML>
<html>
<head>
<title>fshare / <?php echo($_GET['n']); ?></title>
<link rel="stylesheet" type="text/css" href="/style.css"/>
<!--
<?php
$_GET['n'] = preg_replace('/[^a-zA-Z0-9ÜÖÄüöä]/',"",$_GET['n']);
var_dump($_GET);
?>
-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
if(file_exists("/var/www/html/fshare.m-ptr.de/shares/" . $_GET['n'] . "/_spasswd") && !isset($_POST['pw'])) {
    die(pwInp(""));
} else if(file_exists("/var/www/html/fshare.m-ptr.de/shares/" . $_GET['n'] . "/_spasswd") && isset($_POST['pw'])) {
    if(file_get_contents("/var/www/html/fshare.m-ptr.de/shares/" . $_GET['n'] . "/_spasswd") != $_POST['pw']) {
        die(pwInp("Passwort falsch"));
    }
}
#var_dump($_SERVER);
$filesIn = glob("shares/" . $_GET['n'] . "/*");
$files = array();
foreach ($filesIn as $v) {
    $v = str_replace("shares/" . $_GET['n'] . "/", "", $v);
    if($v != "_spasswd" && $v[0] != ".") {
        array_push($files,$v); 
    }
}
sort($files,SORT_STRING | SORT_FLAG_CASE);

?>
<nav> 
<div class="left"><img src="/assets/logo_long_w.svg"/><span>&nbsp; / <?php echo($_GET['n']); ?></span></div>
<?php 
function getDirectorySize($path){
    if( !is_dir( $path ) ) {
        return 0;
    }
    $path   = strval( $path );
    $io     = popen( "ls -ltrR {$path} |awk '{print \$5}'|awk 'BEGIN{sum=0} {sum=sum+\$1} END {print sum}'", 'r' );
    $size   = intval( fgets( $io, 80 ) );
    pclose( $io );
    return $size;
}
$dirSize = intval(getDirectorySize("/var/www/html/fshare.m-ptr.de/shares/" . $_GET['n']));
if(intdiv($dirSize,1000000000) > 0) { // GB
    $dirSizeText = round($dirSize/1000000000,2) . "GB";
} else if(intdiv($dirSize,1000000) > 0) { // MB
    $dirSizeText = round($dirSize/1000000,2) . "MB";
} else { // KB
    $dirSizeText = round($dirSize/1000,2) . "KB";
}?>
<script>
function chkDownloadAll() {
    if(<?php echo(($dirSize > 20000000) ? "false" : "true"); ?> || confirm("Alle Dateien sind insgesamt ca. <?php echo($dirSizeText); ?> groß.\nDownload bestätigen:")) {
        window.location.href = "/fserve.php?sname=<?php echo(urlencode($_GET['n']) . (isset($_POST['pw']) ? ("&pw=" . urlencode($_POST['pw'])) : "")); ?>&zip=1";
    }
}
</script>
<a onclick="chkDownloadAll();" id="downloadAll"><div class="right" title="<?php echo($dirSizeText); ?>"><img src="/assets/download_w.svg"/><span>&nbsp; Alle Dateien</span></div></a>
</nav>
<?php
if(count($files) == 0) {
    echo("<h2>Keine freigegebenen Dateien gefunden</h2><style>nav .right {display:none;}</style>");
} else {
    echo("<div class=\"fgrid\">");
    if(count($files) == 1) {
        echo("<div class=\"item\" style=\"visibility:hidden;\"></div><style>nav .right {display:none;}</style>"); // to center single item
    }
    include("fTypeColor.php");
    $tilesStrings = array();
    foreach ($files as $f) {
        $tmpStr = "";
        $fi = pathinfo("shares/" . $_GET['n'] . "/" . $f);
        $tmpStr .= "<a href=\"/fserve.php?sname=" . urlencode($_GET['n']) . "&fname=" . urlencode($f) . "&pw=" . urlencode($_POST['pw']) . "\" download><div class=\"item\">";
        // main image
        $tmpStr .= "<div class=\"main preview\" style=\"background-image:url(/fserve.php?sname=" . urlencode($_GET['n']) . "&fname=" . urlencode($f) . "&pw=" . urlencode($_POST['pw']) . "&preview=1)\"></div>";
        // backdrop
        if(strtolower($fi['extension']) == "jpg" || strtolower($fi['extension']) == "jpeg" || strtolower($fi['extension']) == "png") {
            $tmpStr .= "<div class=\"preview\" style=\"background-image:url(/fserve.php?sname=" . urlencode($_GET['n']) . "&fname=" . urlencode($f) . "&pw=" . urlencode($_POST['pw']) . "&preview=1)\"></div>";
        } else {
            $tmpStr .= "<div class=\"preview\" style=\"background-color:#dddddd;filter:none;background-image:radial-gradient(#" . fileTypeGetColor(strtolower($fi['extension'])) . "66,#ffffff00)\"></div>";
        }
        $tmpStr .= "<div class=\"sub\"><span class=\"fname\">" . $fi['filename'] . "</span><span class=\"fext\">." . $fi['extension'] . "</span><img class=\"downloadico\" src=\"/assets/download.svg\"></div></div></a>";
        array_push($tilesStrings,$tmpStr);
    }
    $tsOverflow = array();
    if(count($tilesStrings) > 21) {
        $tsOverflow = array_slice($tilesStrings, 21);
        $tilesStrings = array_splice($tilesStrings, 0, 21);
    }
    #$tsOverflow = array_splice($tsOverflow, 0, 3);
    echo(implode($tilesStrings,""));
    echo("</div>\n");
    if(count($tsOverflow) > 0) {
        echo("<script>var echoLater = ['" . implode($tsOverflow,"','") . "'];
        $(window).scroll(function() {
            if($(window).scrollTop() + $(window).height() > $(document).height()-400) {
                $('.fgrid').append(echoLater.shift());
                $('.fgrid').append(echoLater.shift());
                $('.fgrid').append(echoLater.shift());
            }
         });
        </script>\n");
    }
}
?>
<script>
    $("#downloadAll").click(function() {
        $("#downloadAll").find("img").attr('src','/assets/spinner_w.svg');
        var ival = setInterval(function() {
            if(getCookie("downloadDone") == "true") {
                $("#downloadAll").find("img").attr('src','/assets/download_w.svg');
                resetCookie("downloadDone");
                ival = 0;
            }
        },200);
    });
    function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}
function resetCookie(cname) {
  var d = new Date();
  d.setTime(d.getTime());
  var expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=;" + expires + ";path=/";
}
</script>
<?php
function pwInp($errText) {
    $s = "<style>h1{
        margin-top:20vh;
        text-align:center;
    }
    form{
        margin: 2vh auto;
    }
    table {
        margin: 0 auto;
        border-spacing:0;
    }
    input {
        font-size: 1.7em;
        border: 2px #ffffff solid;
        margin: 0;
        padding: 0.2em;
        color: #000000;
        background-color: #ffffff;
        cursor:pointer;
        -webkit-appearance:none;
        outline:none;
        transition: ease-in-out 100ms;
    }
    input:focus, input:hover {
        box-shadow: 0px 0px 10px #ffffff11;
    }
    input[type=\"password\"] {
        background-color: #ffffff22;
        color: #ffffff;
        cursor:text;
    }
    td.etext {
        line-height: 1.8em;
    }
    </style><h1 style=\"font-weight:400;\">Passwort für die Fraigabe <i>" . $_GET['n'] . "</i></h1><form method=\"POST\">";
    $s .= "<table><tr><td><input type=\"password\" placeholder=\"Passwort\" name=\"pw\"/></td>";
    $s .= "<td><input type=\"submit\" value=\"Öffnen\"/></td></tr>";
    if($errText != "") {
        $s .= "<tr><td class=\"etext\">" . $errText . "</td></tr>";
    }
    $s .= "</table></form></body></html>";
    return $s;
}
?>
