<?php
require_once "../../../config/index.php";


/**
 * Process of Attendance PunchCard
 * 
 * Get employee by Barcode
 * Query attendance for another entry of same day
 * Create New entry if there is no any entry on same date
 * Else Update checkout time
 * 
 * Stored Procedure of punch_card()
 * 
 */

if(isset($_GET['barcode'])){
    $conn = OpenCon();

    $barcode = $conn -> real_escape_string($_GET['barcode']);
    $sql = " CALL `punch_card`('".$barcode."');";

    $result = mysqli_query($conn, $sql);

    //This is how to output JSON data.
    $employee= [];

    if(mysqli_num_rows($result)>0){
        while($row=mysqli_fetch_assoc($result))
        {
            array_push($employee, $row);
        }

        echo json_response(200, $employee);

    }else{
        echo json_response(404, null);
    }

    CloseCon($conn);
}else{
  echo json_response(401, null);
}

?>