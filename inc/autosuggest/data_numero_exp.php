<?
include('../conexion.data.php');
include('../adodb/adodb.inc.php');
include('../conexion.data.php');

$conn =& ADONewConnection('mysql');
$conn->Connect($conn_server, $conn_user, $conn_pass, $conn_bd);
$conn->debug=0;


header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
//header('Content-Type: text/html; charset=utf-8');
	
//header("Content-Type: application/json");


$input = trim($_GET['input']);
$len = strlen($input);


$sql = "select distinct numero, caratula ".
	   "from expedientes ".
	   "where numero like '".$input."%' ".
	   "order by numero ".
	   "LIMIT 10";
	   

$rs = $conn->Execute($sql);


/*echo "{\"results\": [";
$arr = array();

while (!$rs->EOF)
{
	$arr[] = "{\"id\": \"".$rs->fields['numero']."\", \"value\": \"". utf8_encode($rs->fields['numero'])."\", \"info\": \"\"}";
						
	$rs->MoveNext();
}
echo implode(", ", $arr);
echo "]}";*/

header("Content-Type: text/xml");

echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?><results>";
while (!$rs->EOF)
{
	echo "<rs id=\"".$rs->fields['numero']."\" info=\"".$rs->fields['caratula']."\">".$rs->fields['numero']."</rs>";
	$rs->MoveNext();
}
echo "</results>";



?>