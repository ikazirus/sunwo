<?php
function json_response($code = 200, $message = null)
{
    
    // clear the old headers
    // header_remove();
    
    // set the actual code
    http_response_code($code);
    
	
    $status = array(
        200 => 'OK',
        201 => 'Created Successfully',
        202 => 'Updated Successfully',
        204 => 'No Content',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        422 => 'Unprocessable Entity',
        500 => 'Internal Server Error'
        );
        
    // ok, validation error, or failure
    header('Status: '.$status[$code]);

    if($message == null){
        $message = $status[$code];
    }
    
    // return the encoded json
    return json_encode(array(
        'status' => $code < 300, // success or not?
        'message' => $message
        ));
}
 ?>