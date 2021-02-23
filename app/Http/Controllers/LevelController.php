<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index(){
        $result = Level::All()->toArray();
        if ($result) {
            array_splice($result,0,1);
            return $this->successResponse($result);
        }
        return $this->notFoundResponse();
    }
}
