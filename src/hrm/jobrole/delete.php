<?php 

require_once "../../../config/index.php";
require_once "../../auth/check_auth.php";

$conn = OpenCon();

$id = $conn -> real_escape_string($_GET['id']);
$sql = "DELETE FROM  job_role WHERE id = ".$id.";";

if (mysqli_query($conn, $sql)) {
  echo  json_response(200,null);
} else {
  echo json_response(500,mysqli_error($conn));
}

CloseCon($conn);

?>
