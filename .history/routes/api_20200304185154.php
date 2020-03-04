<?php
use App\User;
use App\Conteudo;
use App\Comentario;
// php artisan passport:install
Route::post('/cadastro',"UsuarioController@cadastro");
Route::post('/login',"UsuarioController@login");
Route::middleware('auth:api')->put('/perfil', "UsuarioController@perfil");
