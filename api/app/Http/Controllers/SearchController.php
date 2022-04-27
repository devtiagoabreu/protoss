<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;
use App\Models\User;


class SearchController extends Controller
{
    private $loggedUser;

    public function __construct() {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
    }

    public function search(Request $request) {
        $array = ['error'=>'', 'users' => []];

        $txt = $request->input('txt');

        if($txt) {

            // busca de usuários //SELECT * FROM users WHERE name LIKE '%TIA%'
            $userList = User::where('name', 'like', '%' .$txt.'%')->get();
            foreach ($userList as $userItem) {
                $array['users'][] = [
                    'id' => $userItem['id'],
                    'name' => $userItem['name'],
                    'avatar' => url('media/avatars/'.$userItem['avatar'])
                ];
            }

        } else {
            $array['error'] = 'Você não digitou nada na pesquisa!';
            return $array;
        }



        


        return $array;
    }
}
