<?php

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
// php artisan passport:install
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/cadastro', function (Request $request) {
    $data = $request->all();

    $validacao = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    if ($validacao->fails()){
        return $validacao->errors();
    }
    $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
     $user->token = $user->createToken($user->email)->accessToken;
    return $user;
});

Route::post('/login', function (Request $request) {
     $data = $request->all();

     $validacao = Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ]);
    if ($validacao->fails()){
        return $validacao->errors();
    }
    if(Auth::attempt(['email' => $data['email'], 'password' =>  $data['password']])){
        $user = auth()->user();
        $user->token = $user->createToken($user->email)->accessToken;
        return $user;
    }else{
        return [
            'status'=>false
        ];
    }


});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:api')->put('/perfil', function (Request $request) {
    $user = $request->user();
    $data = $request->all();

    if (isset($data['password'])){
        $validacao = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255',  Rule::unique('users')->ignore($user->id)],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        if($validacao->fails()){
            return $validacao->errors();
          }
        $data['password'] = Hash::make($data['password']);
    } else {
        $validacao = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255',  Rule::unique('users')->ignore($user->id)],
        ]);
        if ($validacao->fails()){
            return $validacao->errors();
        }
        $user->name = $data['name'];
        $user->email = $data['email'];

        }


if (isset($data['image'])){

        $time = time();
        $diretorioPai = 'perfils';
        $diretorioImagem = $diretorioPai.DIRECTORY_SEPARATOR.'perfil_id'.$user->id;
        $ext = substr($data['image'], 11, strpos($data['image'], ';') - 11);
        $urlImagem = $diretorioImagem.DIRECTORY_SEPARATOR.$time.'.'.$ext;
        $file = str_replace('data:image/'.$ext.';base64,','',$data['image']);
        $file = base64_decode($file);

        if(!file_exists($diretorioPai)){
            mkdir($diretorioPai,0700);
        }if(!file_exists($diretorioImagem)){
            mkdir($diretorioImagem,0700);
        }
        file_put_contents($urlImagem, $file);

        $user->image = $urlImagem;
    }else {

    }


$user->save();
$user->image = asset($user->image);
$user->token = $user->createToken($user->email)->accessToken;
return $user;

}

);
