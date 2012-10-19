<?
session_start();
$_SESSION["FormatoFecha"] = 'dd/mm/yyyy';
include ('calendario.inc.php');


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>aaaaaaa</title>
</head>

<body>
<? echo CalendarioCreaInput("fecha", ""); ?>
</body>
</html>
