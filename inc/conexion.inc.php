<?

include('../inc/adodb/adodb.inc.php');
include('../inc/conexion.data.php');

$conn =& ADONewConnection('mysql');
$conn->Connect($conn_server, $conn_user, $conn_pass, $conn_bd);
$conn->debug=0;

?>