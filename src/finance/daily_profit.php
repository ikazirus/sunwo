<?php

require_once "../../config/index.php";
require_once "../auth/check_auth.php";
$conn = OpenCon();

$dateString = date("Y");

if(isset($_GET['q'])){
  $dateString = $conn -> real_escape_string($_GET['q']);
}
$data= [];

for ($i=1; $i <= 31; $i++) { 
    $pre ="";
    if($i<10){
        $pre="0";
    }
    $sql .= "CALL `calc_daily_profit`('".$dateString."-".$pre.$i."');";

}

if ($conn -> multi_query($sql)) {
    do {
        // Store first result set
        if ($result = $conn -> store_result()) {
        while ($row = $result -> fetch_assoc()) {
            $row['id']=$row['DAY'];
            if($row['credit']>0||$row['debit']>0)
            array_push($data, $row);
        }
        $result -> free_result();
        }
       
        //Prepare next result set
    } while ($conn -> next_result());
}

if(count($data) >0)
    echo json_response(200, $data);
else
    echo json_response(404, null);

CloseCon($conn);

?>