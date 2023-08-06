<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message= 'Request Successful.')
    {
        $response = [
            'status' => 'success',
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'status' => 'error',
            'data' => null,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['error_message'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
