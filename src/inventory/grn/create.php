<?php 

require_once "../../../config/index.php";
require_once "../../auth/check_auth.php";

$obj = json_decode(file_get_contents("php://input"),true);

if(is_null($obj)){
  echo json_response(400,null);
}
else{
  $conn = OpenCon();

  $sup_id=$obj["sup_id"];
  $payment= $obj["payment"];
  $discount= $obj["discount"];
  $sub_total= $obj["sub_total"];
  $note= $obj["note"];
  $item_list = $obj['itemList'];
  $grn_id = 0;

  $stmt = $conn->prepare("INSERT INTO `grn`
   ( `updated_on`, `sup_id`,`sub_total`, `payment`, `discount`, `status`, `payment_status`, `note`) 
  VALUES ( current_timestamp(), ?, ?, ?, ?, 'PENDING', 'PENDING', ?)");

  $stmt->bind_param("sssss",$sup_id,$sub_total,$payment,$discount,$note);

  $stmt->execute();
  $grn_id= $stmt->insert_id;
  

  // Add GRN Items 
  if($grn_id>0){
    foreach( $item_list as $item){

        $item_id=$item['id'];
        $qty=$item['qty'];
        $rate=$item['rate'];
        $amount=$item['amount'];
        
        $stmtItem = $conn->prepare("INSERT INTO `grn_item` ( `grn_id`, `item_id`, `qty`, `rate`, `amount`) 
        VALUES ( ?, ?, ?, ?, ?);");

        $stmtItem->bind_param("sssss",$grn_id,$item_id,$qty,$rate,$amount);

        $stmtItem->execute();
        $stmtItem->close();

        $stmtStocks = $conn->prepare("CALL `grn_add_stocks`(?, ?, ?);");
        $stmtStocks->bind_param("sss",$item_id,$qty,$grn_id);

        $stmtStocks->execute();
        $stmtStocks->close();

        if (!($stmt->affected_rows>0)) 
          echo json_response(500,htmlspecialchars($stmtItem->error));
    }
  }


  if ($stmt->affected_rows>0) {
    echo json_response(201, null);
  } else {
    echo json_response(500,htmlspecialchars($stmt->error));
  }

  $stmt->close();
  CloseCon($conn);
}
?>
