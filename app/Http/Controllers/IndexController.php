<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Config;


class IndexController extends Controller
{
    public function index(Request $request)
    {
        $dniHorario9 = Config::get('app.dni_list_horario_9');
        $dniJefe = Config::get('app.dni_list_jefe');
        $dni9 = array_map(function($item) {
            list($dni, $nombre) = explode('-', $item, 2);
            return (object) [
                'dni' => $dni,
                'nombre' => $nombre
            ];
        }, $dniHorario9);
        //jefe
        $dniJ = array_map(function($item) {
            list($dni, $nombre) = explode('-', $item, 2);
            return (object) [
                'dni' => $dni,
                'nombre' => $nombre
            ];
        }, $dniJefe);
        return view('welcome', compact('dni9', 'dniJ'));
    }
}