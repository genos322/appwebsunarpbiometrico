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
                // Procesa los datos según sea necesario
                // return response()->json(['success' => 'Archivo subido correctamente.']);
                
                $excelData = Excel::toArray(new UsersImport(), $request->file('file'));
                // Procesa los datos según sea necesario
                $processedData = [];
                foreach ($excelData[0] as $row) {
                    // Aquí procesas cada fila de datos y realizas las operaciones necesarias
                    // Por ejemplo, podrías filtrar ciertas filas, realizar cálculos, etc.
                    // A modo de ejemplo, simplemente almacenaremos los datos en un nuevo array
                    $processedData[] = $row;
                }

                $exportFileName = 'processed_data_' . time() . '.xlsx';
                return Excel::download(new UsersExports($processedData), $exportFileName);//exportFileName es el nombre del archivo que se descargara

            } else {
                // Maneja el caso en que no se envió un archivo válido
                Session::flash('error', ['Por favor, seleccione un archivo Excel válido.']);
                return redirect()->route('inicio');
                // return redirect()->back()->withError(['error' => 'Por favor, seleccione un archivo Excel válido.'], 400);
            }
        }catch (\Exception $e) {
            // Registrar el error en el log de Laravel
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
