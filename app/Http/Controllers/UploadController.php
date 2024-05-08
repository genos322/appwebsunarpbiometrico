<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        // $request->validate([
        //     'file' => 'required|mimes:pdf|max:2048',
        // ]);

        $fileName = time().'.'.$request->file->extension();  
        $request->file->move(public_path('uploads'), $fileName);

        return back()
            ->with('success','You have successfully upload file.')
            ->with('file',$fileName);
    }
}
