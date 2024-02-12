<?php

namespace App\Http\Controllers\google\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FirestoreController extends Controller
{
    /**
     * Search Page API for WEB Call
     *
     * @return \Illuminate\Http\Response
     */
    public function onCreateFscollection(Request $request)
    {
        $status = 'success';
        $status_code = 200;
        $msg = 'No messages';
        $data = [];

        try {
            // Your Firestore collection creation logic here

            // Example log statements
            Log::info('Firestore collection created successfully.', ['request_data' => $request->all()]);
        } catch (\Exception $e) {
            // Log the exception if needed
            $status = false;
            $status_code = 500;
            $msg = $e->getMessage();

            Log::error('Error creating Firestore collection.', [
                'request_data' => $request->all(),
                'error_message' => $msg,
                'exception' => $e,
            ]);
        }

        return response()->json([
            'status' => $status,
            'status_code' => $status_code,
            'message' => $msg,
            'data' => $data,
        ], $status_code);
    }
}
