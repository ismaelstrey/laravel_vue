<?php
use App\User;
use App\Conteudo;
use App\Comentario;
// php artisan passport:install
Route::post('/cadastro',"UsuarioController@cadastro");
Route::post('/login',"UsuarioController@login");
Route::middleware('auth:api')->put('/perfil', "UsuarioController@perfil");
route::get('/testes', function(){
$user = User::find(1);
$user2 = User::find(2);
//  Cria conteudo para um usuario

// $conteudo = Conteudo::all();
// $user->conteudos()->create([
//     'titulo'=>'Conteudo 01',
//     'texto' => 'Aqui o testo',
//     'img'=> 'Aqui ai uma imagem',
//     'link'=>'Aqui vai um link',
//     'date' => date('Y-m-d')
// ]);
// return $user->conteudos;
// _________________FIM__________________

// Mostra quais amigos esse usuario possui
// $user->amigos()->attach($user2->id);
// return $user->amigos;
// _________________FIM__________________

// // Mostra quais amigos esse usuario possui
// $user->amigos()->detach($user2->id);
// return $user->amigos;
// // _________________FIM__________________

// Adiciona amigo se não estiver adicionado e remove caso esteja adicionado
// // add Curtidas
// $conteudo = Conteudo::find(1);
// $user->curtidas()->toggle($conteudo->id);
// // return $conteudo->curtidas()->count();
// return $conteudo->curtidas;
// _________________FIM__________________
// add Comentarios
$conteudo = Conteudo::find(1);
$user->comentarios()->create([
    'conteudo_id' => $conteudo->id,
    'texto' => 'Bha que legal esse conteudo',
    'data' => date('Y-m-d')
]);
$user2->comentarios()->create([
    'conteudo_id' => $conteudo->id,
    'texto' => 'Bem isso que eu estava procurando',
    'data' => date('Y-m-d')
]);
return $conteudo->comentarios;

});
