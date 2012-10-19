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
header ('Content-Type: text/html; charset=utf-8');
	
header ("Content-Type: application/json");


$input = trim(strtolower($_GET['input']));
$len = strlen($input);


$sql = "select id_categoria, categoria ".
	   "from categorias ".
	   "where upper(categoria) like '".strtoupper($input)."%' ".
	   "order by categoria ".
	   "LIMIT 10";

$rs = $conn->Execute($sql);


echo "{\"results\": [";
$arr = array();

while (!$rs->EOF)
{
	$categoria = str_replace('"', '\"', utf8_encode($rs->fields['categoria']));
	$arr[] = "{\"id\": \"".$rs->fields['id_categoria']."\", \"value\": \"".$categoria."\", \"info\": \"\"}";
						
	$rs->MoveNext();
}
echo implode(", ", $arr);
echo "]}";


?>