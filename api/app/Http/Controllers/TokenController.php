<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Token;
use App\Models\User;
use App\Models\UserRelation;


class TokenController extends Controller
{
    private $loggedUser;

    public function __construct() {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
    }

    public function tokenCreate(Request $request) {
      $array = ['error'=>''];

      //gerando token 
      $min = 1000;
      $max = 9999; 
     
      $type = $request->input('type');
      $token = mt_rand($min, $max);
      
      if($type) {
          
          switch($type) {
              case 'authentication':
                  if(!$token) {
                      $array['error'] = 'Token não pode ser gerado!';
                      return $array;
                  }
              break;
              default:
                  $array['error'] = 'Solicitação negada pelo servidor!';
                  return $array;
              break;
          }

          if ($token) {
              $newToken = new Token();
              $newToken->id_user = $this->loggedUser['id'];
              $newToken->type = $type;
              $newToken->created_at = date('Y-m-d H:i:s');
              $newToken->token = $token;
              $newToken->save();
          }

          } else {
              $array['error'] = 'Alguma informação necessária não foi enviada!';
              return $array;
          }
      
      return $array;
  }

    public function tokenRead(Request $request, $id = false) {
      $array = ['error'=>''];

      if ($id == false) {
        $id = $this->loggedUser['id'];
      }

      //2. trazendo o último token
      $token = Token::where('id_user', $id)
      ->orderBy('created_at', 'DESC')
      ->limit(1)
      ->get();

      //3. preencher as informações adicionais do token
      //$token = $this->_tokenListToObject($tokenList, $id);
      
      $array['token'] = $token;
      
      return $array;
  }

    private function _tokenListToObject($tokenList, $loggedId) {
      foreach($tokenList as $tokenKey => $tokenItem) {
          
        //verificar se o token é do usuário logado
        if ($tokenItem['id_user'] == $loggedId) {
            $tokenList[$tokenKey]['mine'] = true;
        } else {
            $tokenList[$tokenKey]['mine'] = false;
        }
      }

      return $tokenList;
  }

    
}
