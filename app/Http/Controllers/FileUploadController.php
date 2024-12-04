<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FileUploadController extends Controller
{
    public function StoreUploadedFile(Request $request)
    {
        /* Validate the uploaded file */
        $validator = Validator::make($request->all(), [
            'image' => 'required|mimes:png,jpg,jpeg,pdf,docx'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid file type or file too large.',
                'errors' => $validator->errors()
            ], 400);
        }

        /* Check if the file exists in the request */
        $file = $request->file('image');

        if ($file) {
            $randomNumber = rand(0, 10000000);
            $fileName = str_replace(' ','', $file->getClientOriginalName().'-'.$randomNumber.'.'.$file->getClientOriginalExtension());
            $file->move(public_path().'/uploads/', $fileName);

            /* write logic here to enter data in database OR any other operations */

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully.',
                'path' => $fileName
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'No file uploaded.'
        ], 400);
    }
}
