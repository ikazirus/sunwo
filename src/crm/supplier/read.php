<?php

require_once "../../../config/index.php";
require_once "../../auth/check_auth.php";

$conn = OpenCon();

$sql = "SELECT s.* , SUM((g.sub_total*(1-g.discount/100))-g.payment) AS outstanding 
FROM `supplier` s 
LEFT JOIN `grn` g ON g.sup_id = s.id 
GROUP BY s.id;";

if(isset($_GET['id'])){
  $id = $conn -> real_escape_string($_GET['id']);
  $sql = "SELECT s.* , SUM((g.sub_total*(1-g.discount/100))-g.payment) AS outstanding 
  FROM `supplier` s
  LEFT JOIN `grn` g ON g.sup_id = s.id 
  WHERE id=".$id.";";
}



$result = mysqli_query($conn, $sql);

//This is how to output JSON data.
$data= [];

if(mysqli_num_rows($result)>0){
  while($row=mysqli_fetch_assoc($result))
  {
    array_push($data, $row);
  }
    
  echo json_response(200, $data);
}else{
  echo json_response(404, null);
}

CloseCon($conn);

?>