<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Rules\validarEmail;
use App\Rules\validarNickname;

class PerfilUsuarioController extends Controller
{
    public function index()
    {
        $userActual = Auth::user();
        return view('/editarPerfil', ['user' => $userActual]);
    }

    public function editarPerfil(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->id != $id) {
            abort(403, 'No está autorizado para realizar la acción');
        }

        $userAmodificar  = User::where('id', $id)->get()->first();
        $data = $request->all();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nickname' => ['required', 'string', 'max:255', new validarNickname($userAmodificar->id)],
            'email' => ['required', 'max:255', new validarEmail($userAmodificar->id)],
        ]);

        /**Si el nombre cambio */
        if (strcmp($data['name'], $userAmodificar->name) != 0)
            $userAmodificar->update(['name' => $data['name']]);
        /**Si el nickname cambio */
        if (strcmp($data['nickname'], $userAmodificar->nickname) != 0)
             $userAmodificar->update(['nickname' => $data['nickname']]);
        /**Si el email cambio */
        if (strcmp($data['email'], $userAmodificar->email) != 0)
             $userAmodificar->update(['email' => $data['email']]);
    
            return view('/home',['user'=>$userAmodificar]);
       }
}
