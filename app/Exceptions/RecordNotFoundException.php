<?php

namespace App\Exceptions;

use Exception;

class RecordNotFoundException extends Exception
{
    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        $respone = [
            'success' => false,
            'message' => 'Record not found'
        ];
        return response()->json($respone, 404);
    }
}
