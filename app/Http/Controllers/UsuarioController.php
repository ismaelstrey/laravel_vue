<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\User;

class UsuarioController extends Controller
{
        public function login(Request $request)
        {
                $data = $request->all();

            $validacao = Validator::make($data, [
                    'email' => ['required', 'string', 'email', 'max:255'],
                    'password' => ['required', 'string'],
                ]);
                
            if ($validacao->fails()){
                return [
                    'status'=>false,
                    'validacao' => true,
                    'erros'=>$validacao->errors()
                ];                 
            }

            if(Auth::attempt(['email' => $data['email'], 'password' =>  $data['password']])){
                $user = auth()->user();
                $user->token = $user->createToken($user->email)->accessToken;
                $user->image = asset($user->image);
                return [
                    'status' => true,
                    "usuario" => $user
                ];
            }else{
                return [
                    'status'=>false
                ];
            }


        }
         public function cadastro(Request $request)
        {
            $data = $request->all();
            $validacao = Validator::make($data, [
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
                ]);
            if ($validacao->fails()){
                 return [
                    'status'=>false,
                    'validacao' => true,
                    'erros'=>$validacao->errors()
                ]; 
            }
            $image = '/perfils/user.png';
            $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'image' => $image
                ]);
             $user->token = $user->createToken($user->email)->accessToken;
             $user->image = asset($image);
            return [
                'status' => true,
                "usuario" => $user
            ];

        }        
        public function perfil(Request $request)
        {
                $user = $request->user();
                $data = $request->all();
                if (isset($data['password'])){
                $validacao = Validator::make($data, [
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255',  Rule::unique('users')->ignore($user->id)],
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
                    ]);
                    if($validacao->fails()){
                        return [
                            'status'=>false,
                            'validacao' => true,
                            'erros'=>$validacao->errors()
                        ];  
                    }
                    $data['password'] = Hash::make($data['password']);
                    } else {
                    $validacao = Validator::make($data, [
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255',  Rule::unique('users')->ignore($user->id)],
                    ]);
                    if ($validacao->fails()){
                        return [
                            'status'=>false,
                            'validacao' => true,
                            'erros'=>$validacao->errors()
                        ];  
                    }
                    $user->name = $data['name'];
                    $user->email = $data['email'];
                    }
                    if (isset($data['image'])){
                    Validator::extend('base64image', function ($attribute, $value, $parameters, $validator) {
                    $explode = explode(',', $value);
                    $allow = ['png', 'jpg', 'svg','jpeg'];
                    $format = str_replace(
                        [
                            'data:image/',
                            ';',
                            'base64',
                        ],
                        [
                            '', '', '',
                        ],
                        $explode[0]
                    );
                    // check file format
                    if (!in_array($format, $allow)) {
                        return false;
                    }
                    // check base64 format
                    if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $explode[1])) {
                        return false;
                    }
                    return true;
                });

                $validacao = Validator::make($data, [
                    'imagem' => 'base64image',

                ],['base64image'=>'Imagem inválida']);

                if($validacao->fails()){
                 return [
                    'status'=>false,
                    'validacao' => true,
                    'erros'=>$validacao->errors()
                ];  
            }
                // fim validação
                $time = time();
                $diretorioPai = 'perfils';
                $diretorioImagem = $diretorioPai.DIRECTORY_SEPARATOR.'perfil_id'.$user->id;
                $ext = substr($data['image'], 11, strpos($data['image'], ';') - 11);
                $urlImagem = $diretorioImagem.DIRECTORY_SEPARATOR.$time.'.'.$ext;
                $file = str_replace('data:image/'.$ext.';base64,','',$data['image']);
                $file = base64_decode($file);

                if(!file_exists($diretorioPai)){
                mkdir($diretorioPai,0700);

                }
                if($user->image){
                    if(file_exists($user->image)){
                        unlink($user->image);
                    }
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
                return [
                'status' => true,
                "usuario" => $user
            ];
        }
}
