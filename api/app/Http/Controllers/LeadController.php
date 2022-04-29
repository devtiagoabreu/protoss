<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lead;

class LeadController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', [
            'except'=>[
                'create', 
                'unauthorized'
            ]
        ]);
    }

    public function create(Request $request) {
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
