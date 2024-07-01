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
    
    public function insert(Request $request)
    {
        $filePath = storage_path('data/config.json');

        // Verificar si el archivo existe
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'Archivo no encontrado'], 404);
        }

        // Leer el contenido del archivo
        $jsonContent = file_get_contents($filePath);

        // Decodificar el contenido JSON
        $data = json_decode($jsonContent, true);
        // Insertar los datos
        $data['dni_list'][] = [
            'id' => $request->input('id'),//genera un id unico
            'dni' => trim($request->input('dni')),
            'nombre' => trim($request->input('name')),
            'rol' => trim($request->input('rol'))
        ];

        // Codificar los datos actualizados en formato JSON
        $updatedJsonContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // Escribir los datos actualizados en el archivo
        file_put_contents($filePath, $updatedJsonContent);

        return response()->json(['message' => 'Usuario registrado correctamente']);
    }

    public function update(Request $request)
    {
        $filePath = storage_path('data/config.json');

        // Verificar si el archivo existe
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'Archivo no encontrado'], 404);
        }

        // Leer el contenido del archivo
        $jsonContent = file_get_contents($filePath);

        // Decodificar el contenido JSON
        $data = json_decode($jsonContent, true);
        $id = trim(explode('+',$request->input('id'))[0]);
        $newDni = trim($request->input('dni'));
        $newName = trim($request->input('name'));
        foreach ($data['dni_list'] as &$item) {//el & es para que se actualice el valor en el array
            if ($item['id'] == $id) {
                $item['dni'] = $newDni;
                $item['nombre'] = $newName;
                break;
            }
        }
        // Codificar los datos actualizados en formato JSON
        $updatedJsonContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // Escribir los datos actualizados en el archivo
        file_put_contents($filePath, $updatedJsonContent);

        return response()->json(['message' => 'Datos actualizados correctamente']);
    }

}
