<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Lead;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', [
            'except'=>[
                'login', 
                'create',
                'createLead', 
                'unauthorized'
            ]
        ]);
    }

    public function unauthorized() {
        return response()->json(['error'=>'Não Autorizado'], 401);
    }

    public function login(Request $request) {
        $array = ['error'=>''];

        $email = $request->input('email');
        $password = $request->input('password');

        if ($email && $password) {
            $token = auth()->attempt([
                'email' => $email,
                'password' => $password
            ]);
    
            if (!$token) {
                $array['error'] = 'E-mail e/ou senha incorretos!';
                return $array;
            }
    
            $array['token'] = $token;
            return $array;
        }

        $array['error'] = 'Dados incompletos...';
        return $array;
    }

    public function logout() {
        auth()->logout();
        return ['error'=>''];
    }

    public function refresh() {
        $token = auth()->refresh();
        return [
            'error'=>'',
            'token' => $token
        ];
    }

    public function create(Request $request) {
        $array = ['error'=>''];

        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $birthdate = $request->input('birthdate');
                
        if($name && $email && $password && $birthdate) {
            //validando data de nascimento
            if(strtotime($birthdate) == false) {
                $array['error'] = 'Data de nascimento inválida!';
                return $array;
            }
            //verificar se existe email cadastrado
            $emailExists = User::where('email', $email)->count();
            if ($emailExists === 0) {
                
                $hash = password_hash($password, PASSWORD_DEFAULT);

                $newUser = new User();
                $newUser->name = $name;
                $newUser->email = $email;
                $newUser->password = $hash;
                $newUser->birthdate = $birthdate;
                $newUser->save();

                $token = auth()->attempt([
                    'email' => $email,
                    'password' => $password
                ]);
                if (!$token) {
                    $array['error'] = 'Erro ao criar usuário!';
                    return $array;
                }

                $array['token'] = $token;

            } else {
                $array['error'] = 'E-mail já cadastrado!'; 
                return $array; 
            }
        } else {
            $array['error'] = 'Por Favor Preencha todos os campos.';
            return $array;
        }      

        return $array;
    }

    public function createLead(Request $request) {
        $array = ['error'=>''];

        $id_product = $request->input('id_product');
        $name = $request->input('name');
        $email = $request->input('email');
        $contact_a = $request->input('contact_a');

        if (!$id_product) {
            // id_product default=1
            $id_product = 1; 
        }
                
        if($name && $email && $contact_a) {
            //verificar se existe email e contato cadastrado
            $emailExists = Lead::where('email', $email)->count();
            $contactExists = Lead::where('contact_a', $contact_a)->count();
            
            if ($emailExists === 0) {
                if ($contactExists === 0 ) {

                    $newLead = new Lead();
                    $newLead->id_product = $id_product;
                    $newLead->name = $name;
                    $newLead->email = $email;
                    $newLead->contact_a = $contact_a;
                    $newLead->save();

                } else {
                    $array['error'] = 'Celular já cadastrado!'; 
                    return $array;
                }
               
            } else {
                $array['error'] = 'E-mail já cadastrado!'; 
                return $array; 
            }
        } else {
            $array['error'] = 'Por Favor Preencha todos os campos.';
            return $array;
        }      

        return $array;
    }
}
