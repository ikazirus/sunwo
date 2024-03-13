<?php 

require_once "../../../config/index.php";
require_once "../../auth/check_auth.php";

$obj = json_decode(file_get_contents("php://input"),true);

if(is_null($obj)){
  echo json_response(400,null);
}
else{
  $conn = OpenCon();

  $quotation_id=$obj["quotation_id"];
  $issued_qty=$obj["issued_qty"];
  $note= $obj["note"];
  $item_list = $obj['itemList'];
  $gin_id = 0;

  $stmt = $conn->prepare("INSERT INTO `gin`
    ( `quotation_id`, `issued_qty`, `note`, `status`, `issued_on`, `updated_on`) 
    VALUES ( ?, ?, ?, 'PENDING', current_timestamp(), current_timestamp())");

  $stmt->bind_param("sss",$quotation_id,$issued_qty,$note);

  $stmt->execute();
  $gin_id= $stmt->insert_id;
  

  // Add GRN Items 
  if($gin_id>0){
    foreach( $item_list as $item){

        $item_id=$item['id'];
        $qty=$item['qty'];
        
        $stmtItem = $conn->prepare("INSERT INTO `gin_item` ( `gin_id`, `item_id`, `qty`) 
        VALUES (?, ?, ?);");

        $stmtItem->bind_param("sss",$gin_id,$item_id,$qty);
        $stmtItem->execute();
        $stmtItem->close();

        $stmtStocks = $conn->prepare("CALL `gin_remove_stocks`(?, ?,?);");
        $stmtStocks->bind_param("sss",$item_id,$qty,$gin_id);

        $stmtStocks->execute();
        $stmtStocks->close();

        if (!($stmt->affected_rows>0)) 
          echo json_response(500,htmlspecialchars($stmtItem->error));
    }
  }


  if ($stmt->affected_rows>0) {
    echo json_response(200, null);
  } else {
    echo json_response(500,htmlspecialchars($stmt->error));
  }

  $stmt->close();
  CloseCon($conn);
}
?>
