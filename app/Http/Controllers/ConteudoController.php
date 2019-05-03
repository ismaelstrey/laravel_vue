<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conteudo;

class ConteudoController extends Controller
{
    function adicionar (Request $request){
    $data = $request->all();
    $user = $request->user();

// validação
        $conteudo = new Conteudo;
        $conteudo->titulo = $data['titulo'];
        $conteudo->texto  = $data['texto'];
        $conteudo->img    = $data['img'] ? $data['img']: '#';
        $conteudo->link   = $data['link'] ? $data['link']: '#';
        $conteudo->date   = date('Y-m-d H:i:s');

        $user->conteudos()->save($conteudo);
        return [
            'status' => true,
            "conteudos" => $user->conteudos
        ];
    }
}
