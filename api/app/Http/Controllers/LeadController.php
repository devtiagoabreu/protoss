<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lead;
use App\Models\User;
use App\Models\UserRelation;

class LeadController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
    }

    
}
