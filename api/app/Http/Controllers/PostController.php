<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\PostComment;

class PostController extends Controller
{
    private $loggedUser;

    public function __construct() {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
    }

    public function like($id) {
        $array = ['error'=>''];

        // 1. verificar se o post existe
        $postExists = Post::find($id);
        if($postExists) {
             // 2. verificar se o usuário já deu like neste post
            $isLiked = PostLike::where('id_post', $id)
            ->where('id_user', $this->loggedUser['id'])
            ->count();

            if ($isLiked > 0) {
                // 2.1 se sim, remover
                $pl = PostLike::where('id_post', $id)
                ->where('id_user', $this->loggedUser['id'])
                ->first();
                $pl->delete();

                $array['isLiked'] = false;
            } else {
                // 2.1 se não, adicionar
                $newPostLike = new PostLike();
                $newPostLike->id_post = $id;
                $newPostLike->id_user = $this->loggedUser['id'];
                $newPostLike->created_at = date('Y-m-d H:i:s');
                $newPostLike->save();

                $array['isLiked'] = true;
            }

            $array['likeCount'] = PostLike::where('id_post', $id)->count();
            
        } else {
            $array['error'] = 'Post não existe!';
            return $array;
        }

        return $array;
    }

    public function comment(Request $request, $id) {
        $array = ['error'=>''];

        $txt = $request->input('txt');

        // 1. verificar se o post existe
        $postExists = Post::find($id);
        if($postExists) {
           if($txt) {

                $newComment = new PostComment();
                $newComment->id_post = $id;
                $newComment->id_user = $this->loggedUser['id'];
                $newComment->created_at = date('Y-m-d H:i:s');
                $newComment->body = $txt;
                $newComment->save();

           } else {
            $array['error'] = 'Por favor, envie um texto para o comentário!';
            return $array;
           } 
            
        } else {
            $array['error'] = 'Post não existe!';
            return $array;
        }

        return $array;
    }
}
