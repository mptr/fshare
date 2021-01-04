<?php
if(isset($_POST['sn'])) {
    header("Location: https://fshare.m-ptr.de/" . $_POST['sn']);
    die();
}
?>
<!DOCTYPE HTML>
<html>
<head>
<link action="index.php" rel="stylesheet" type="text/css" href="style.css">
<style>
h1{
        margin-top:20vh;
        text-align:center;
        font-weight: 400;
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
    input[type="text"] {
        background-color: #ffffff22;
        color: #ffffff;
        cursor:text;
    }
    td.etext {
        line-height: 1.8em;
    }
    </style>
<title>fshare.m-ptr.de</title>
</head>
<body>
<h1>fshare.m-ptr.de</h1>
<form method="POST">
<table>
<tbody>
<tr>
<td><input type="text" placeholder="Sharename" name="sn"></td>
<td><input type="submit" value="Öffnen"></td>
</tr>
</tbody>
</table>
</form>
</body>
</html>