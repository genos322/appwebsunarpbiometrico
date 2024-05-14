<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Export\UsersExports;
use App\Import\UsersImport;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
       
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
        } else {
            // Maneja el caso en que no se envió un archivo válido
            return redirect()->back()->with('error', 'Por favor, seleccione un archivo Excel válido.');
        }
        // $request->validate([
        //     'file' => 'required|mimes:pdf|max:2048',
        // ]);

  
    }
}
