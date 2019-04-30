<?php
use App\User;
use App\Conteudo;
use App\Comentario;
// php artisan passport:install
Route::post('/cadastro',"UsuarioController@cadastro");
Route::post('/login',"UsuarioController@login");
Route::middleware('auth:api')->get('/usuario', 'UsuarioController@usuario');
Route::middleware('auth:api')->put('/perfil', "UsuarioController@perfil");
route::get('/testes', function(){
$user = User::find(1);
$conteudo = Conteudo::all();
$user->conteudos()->create([
    'titulo'=>'Conteudo 01',
    'texto' => 'Aqui o testo',
    'img'=> 'Aqui ai uma imagem',
    'link'=>'Aqui vai um link',
    'date' => date('Y-m-d')
]);
return $user->conteudos;
});
