<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Config;


class IndexController extends Controller
{
    public function index(Request $request)
    {
        $filePath = storage_path('data/config.json');

        // Verificar si el archivo existe
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'Archivo no encontrado'], 404);
        }
        $jsonContent = file_get_contents($filePath);

        // Decodificar el contenido JSON
        $data = json_decode($jsonContent, true);
        return view('welcome', compact('data'));
    }
}