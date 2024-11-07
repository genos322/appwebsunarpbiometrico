<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Export\UsersExports;
use App\Import\UsersImport;
use Illuminate\Support\Facades\Session;


class UploadController extends Controller
{
    public function upload(Request $request)
    {
        try {
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                // Verificar el tipo MIME para asegurarse de que es un archivo Excel
                $mimeType = $request->file('file')->getMimeType();
                
                if (!in_array($mimeType, ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])) {
                    Session::flash('error', ['Por favor, seleccione un archivo Excel válido.']);
                    return redirect()->route('inicio');
                }
        
                // Procesa los datos según sea necesario
                $excelData = Excel::toArray(new UsersImport(), $request->file('file'));
        
                $processedData = [];
                foreach ($excelData[0] as $row) {
                    $processedData[] = $row;
                }
        
                $exportFileName = 'processed_data_' . time() . '.xlsx';
                return Excel::download(new UsersExports($processedData,$request->input('txttolerancia')), $exportFileName);
        
            } else {
                // Maneja el caso en que no se envió un archivo válido
                Session::flash('error', ['Por favor, seleccione un archivo Excel válido.']);
                return redirect()->route('inicio');
            }
        } catch (\Exception $e) {
            \Log::error('Error al procesar el archivo Excel: ' . $e->getMessage());
            
            // Maneja cualquier excepción que pueda ocurrir
            Session::flash('error', [$e->getMessage()]);
            return redirect()->route('inicio');
        }
        
        
       
       
        // $request->validate([
        //     'file' => 'required|mimes:pdf|max:2048',
        // ]);

  
    }
}
