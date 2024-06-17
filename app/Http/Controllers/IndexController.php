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
        return view('welcome', compact('dniHorario9', 'dniJefe'));
    }
}