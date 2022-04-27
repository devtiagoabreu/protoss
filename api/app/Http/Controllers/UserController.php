<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserRelation;
use App\Models\Post;
use Faker\Provider\Image as ProviderImage;
use Image;

class UserController extends Controller
{
    private $loggedUser;

    public function __construct() {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
    }

    public function update(Request $request) {
        $array = ['error'=>''];

        $name = $request->input('name');
        $email = $request->input('email');
        $birthdate = $request->input('birthdate');
        $city = $request->input('city');
        $work = $request->input('work');
        $password = $request->input('password');
        $password_confirm = $request->input('password_confirm');

        $user = User::find($this->loggedUser['id']);

        if($name) {
            $user->name = $name;
        }

        if($email) {
            if($email != $user->email) {
                $emailExists = User::where('email', $email)->count();
                if($emailExists === 0) {
                    $user->email = $email;
                } else {
                    $array['error'] = 'E-mail já existe!';
                    return $array;
                }
            }
        }

        if($birthdate) {
            if(strtotime($birthdate) === false) {
                $array['error'] = 'Data de nascimento inválida!';
                return $array;
            } 
            $user->birthdate = $birthdate;
        }

        if($city) {
            $user->city = $city;
        }

        if($work) {
            $user->work = $work;
        }

        if($password && $password_confirm) {
            if($password === $password_confirm) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $user->password = $hash;
            } else {
                $array['error'] = 'Sua confirmação de senha não corresponde com a nova senha!';
                return $array;
            }
        }

        $user->save();
        
        return $array;
    }

    public function updateAvatar(Request $request) {
        $array = ['error'=>''];

        $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png'];
        
        $image = $request->file('avatar');

        if($image) {
            if(in_array($image->getClientMimeType(), $allowedTypes)) {

                $filename = md5(time().rand(0,9999)).'.jpg';

                $destPath = public_path('/media/avatars');

                $img = Image::make($image->path())
                    ->fit(200,200)
                    ->save($destPath.'/'.$filename);

                    $user = User::find($this->loggedUser['id']);
                    $user->avatar = $filename;
                    $user->save();

                    $array['url'] = url('/media/avatars/'.$filename);
            } else {
                $array['error'] = 'Arquivo não importado!';
                return $array;
            }
        } else {
            $array['error'] = 'Arquivo não enviado!';
            return $array;
        }
        return $array;
    }

    public function updateCover(Request $request) {
        $array = ['error'=>''];

        $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png'];
        
        $image = $request->file('cover');

        if($image) {
            if(in_array($image->getClientMimeType(), $allowedTypes)) {

                $filename = md5(time().rand(0,9999)).'.jpg';

                $destPath = public_path('/media/covers');

                $img = Image::make($image->path())
                    ->fit(850,310)
                    ->save($destPath.'/'.$filename);

                    $user = User::find($this->loggedUser['id']);
                    $user->cover = $filename;
                    $user->save();

                    $array['url'] = url('/media/covers/'.$filename);
            } else {
                $array['error'] = 'Arquivo não importado!';
                return $array;
            }
        } else {
            $array['error'] = 'Arquivo não enviado!';
            return $array;
        }
        return $array;
    }

    public function read($id = false) {
        $array = ['error'=>''];

        if ($id) {
            $info = User::find($id);
            if (!$info) {
                $array['error'] = 'Este usuário não existe!';
                return $array;
            }
        } else {
            $info = $this->loggedUser;
        }

        $info['avatar'] = url('media/avatars/'.$info['avatar']);
        $info['cover'] = url('media/covers/'.$info['cover']);

        //operador ternário exemplo do if comentado abaixo - condição? valor se for verdareiro : valor se for falso
        $info['me'] = ($info['id'] == $this->loggedUser['id']) ? true : false;
        /*
        if ($info['id'] == $this->loggedUser['id']) {
            $info['me'] = true;
        }
        */

        //verificando a idade do usuário
        $dateFrom = new \DateTime($info['birthdate']);
        $dateTo = new \DateTime('today');
        $info['age'] = $dateFrom->diff($dateTo)->y;

        //Verificando quantidade seguidores
        $info['followersCount'] = UserRelation::where('user_to', $info['id'])->count();
        $info['followingCount'] = UserRelation::where('user_from', $info['id'])->count();

        //quantidade de fotos
        $info['photoCount'] = Post::where('id_user', $info['id'])
        ->where('type', 'photo')
        ->count();
        
        //verificar se usuário logado está seguindo o usuário visitado
        $hasRelation = UserRelation::where('user_from', $this->loggedUser['id'])
        ->where('user_to', $info['id'])
        ->count();
        $info['isFollowing'] = ($hasRelation > 0) ? true : false;
        
        //inserindo todas as informações do array
        $array['data'] = $info;

        return $array;
    }
}
