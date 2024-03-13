<?php
require_once "../../config/index.php";
require_once "../auth/check_auth.php";

$conn = OpenCon();

if(isset($_GET['year']) && isset($_GET['month']) ){
  $year = $conn -> real_escape_string($_GET['year']);
  $month = $conn -> real_escape_string($_GET['month']);

  $sql = "SELECT e.id, e.name,
  e.credits,
  SEC_TO_TIME( SUM( TIME_TO_SEC( `work_hours` ) ) ) AS total_work_hours, 
  COUNT(a.id) AS total_work_days, 
  SEC_TO_TIME(SUM(TIME_TO_SEC( `work_hours` ) )/ COUNT(*)) AS average_work_hours,
  SEC_TO_TIME(SUM(TIME_TO_SEC( `work_hours` )-(8*60*60) )) AS ot_hours
  FROM  `employee` e
  INNER JOIN `attendance` a ON e.id = a.emp_id
  WHERE a.date LIKE '".$year."-".$month."-%'
  GROUP BY a.emp_id;";
}

$result = mysqli_query($conn, $sql);

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