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
                $excelData = Excel::toArray(new UsersImport(), $request->file('file'));
                // Procesa los datos según sea necesario
                $processedData = [];
                foreach ($excelData[0] as $row) {
                    // Aquí procesas cada fila de datos y realizas las operaciones necesarias
                    // Por ejemplo, podrías filtrar ciertas filas, realizar cálculos, etc.
                    // A modo de ejemplo, simplemente almacenaremos los datos en un nuevo array
                    $processedData[] = $row;
                }
                // $type = $processedData[0][0];//tipo de contrato
                // $name = $processedData[0][1];//nombre del personal
                // $dni = $processedData[0][2];//dni del personal
                // $fecha = $processedData[0][3];//fecha asistencias
                // $locacion = $processedData[0][4];//locacion id
                // $codVerificacion = $processedData[0][6];//codigo de verificacion
                // Crea un nuevo archivo Excel con los datos procesados
                // return dd($processedData);
                $exportFileName = 'processed_data_' . time() . '.xlsx';
                return Excel::download(new UsersExports($processedData), $exportFileName);//exportFileName es el nombre del archivo que se descargara
                // $exportFileName = 'processed_data_' . time() . '.xlsx';
                // $path = storage_path('app/public/' . $exportFileName);
                // Excel::store(new UsersExports($processedData), 'public/' . $exportFileName);

                // return response()->json([
                //     'file_url' => asset('storage/' . $exportFileName),
                //     'message' => 'Archivo generado con éxito'
                // ]);
            } else {
                // Maneja el caso en que no se envió un archivo válido
                Session::flash('error', ['Por favor, seleccione un archivo Excel válido.']);
                return redirect()->route('inicio');
                // return redirect()->back()->withError(['error' => 'Por favor, seleccione un archivo Excel válido.'], 400);
            }
        }catch (\Exception $e) {
            // Maneja cualquier excepción que pueda ocurrir
            // return response()->json(['error' => $e->getMessage()], 400);
            Session::flash('error', [$e->getMessage()]);//flash es para
            return redirect()->route('inicio');
        }
       
       
        // $request->validate([
        //     'file' => 'required|mimes:pdf|max:2048',
        // ]);

  
    }
}
