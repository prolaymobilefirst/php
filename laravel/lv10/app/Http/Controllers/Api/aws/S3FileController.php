<?php

namespace App\Http\Controllers\api\aws;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class S3FileController extends Controller
{
    public function onUpload(Request $request)
    {
        $data=[];
        $error=false;
        $code=Response::HTTP_OK;
        $status_code=Response::HTTP_OK;
        $msgs='';

        try {
            // Check if the request contains a file
            if (!$request->hasFile('file_to_upload') && !$request->has('upload_path')) {
                $error=true;
                $code=Response::HTTP_BAD_REQUEST;
                $msgs='No file was provided in the request.';
                $status_code=Response::HTTP_BAD_REQUEST;
            }

            $file = $request->file('file_to_upload');


            // Check if the file is valid (not empty)
            if ($file && $file->isValid() && $request->filled('upload_path')) {
                // 'uploads' is the S3 folder where the file will be stored
                $upload_path=$request->input('upload_path');
                //$path = $file->store($upload_path, 's3');

                // Use getContent() to get the file content
                $content = $file->getContent();

                // Upload the file to S3
                $path = Storage::disk('s3')->put($upload_path, $content);


                if($path){
                    $error=true;
                    $msgs='File uploaded successfully.';
                    $data = ['path' => $path];
                }else{
                    $code=Response::HTTP_BAD_REQUEST;
                    $msgs='File not uploaded';
                    $status_code=Response::HTTP_BAD_REQUEST;
                }
            } else {
                throw new \Exception('The file could not be uploaded due to an unknown error.');
            }
        } catch (\Exception $e) {
            $error=true;
            $code=Response::HTTP_INTERNAL_SERVER_ERROR;
            $msgs=$e->getMessage();
            $status_code=Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json([
            'error' => $error,
            'code' => $code,
            'message' => $msgs,
            'data' => $data
        ], $status_code);
    }


    public function onListFiles()
    {
        $data=[];
        $error=false;
        $code=Response::HTTP_OK;
        $status_code=Response::HTTP_OK;
        $msgs='';

        try{

            $files = Storage::disk('s3')->files('/');
            $directories = Storage::disk('s3')->directories('/');

            $code=Response::HTTP_OK;
            $msgs='Files & Folders Listed';
            $status_code=Response::HTTP_OK;

            $data=['files'=>$files,'folders'=>$directories];

        } catch (\Exception $e) {
            $error=true;
            $code=Response::HTTP_INTERNAL_SERVER_ERROR;
            $msgs=$e->getMessage();
            $status_code=Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json([
            'error' => $error,
            'code' => $code,
            'message' => $msgs,
            'data' => $data
        ], $status_code);
    }
}
