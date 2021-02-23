<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function store(){
        $result = User::with('level')->get();
        if ($result) {
            return $this->successResponse($result);
        }
        return $this->notFoundResponse();
    }

    public function index()
    {
        $result = User::with('level')->get();
        if ($result) {
            return $this->successResponse($result);
        }
        return $this->notFoundResponse();
    }

    public function show($id)
    {
        $result = User::with('level')->find($id);
        if ($result) {
            return $this->successResponse($result);
        }
        return $this->notFoundResponse();
    }

    public function blokir(Request $request, int $id)
    {
        try{
            $result = User::find($id)->update([
                'status_code' => 4,
                'status' => 'blokir',
            ]);

            if ($result) {
                return $this->successResponse();
            }
            return $this->errorResponse();
        }catch (\Throwable $th){
            return $this->errorResponse($th->getMessage());
        }
    }

    public function aktif(Request $request, int $id)
    {
        try{
            $result = User::find($id)->update([
                'status_code' => 2,
                'status' => 'aktif',
            ]);

            if ($result) {
                return $this->successResponse();
            }
            return $this->errorResponse();
        }catch (\Throwable $th){
            return $this->errorResponse($th->getMessage());
        }
    }

    public function resetpassword(Request $request, int $id){
        $password = $this->generateRandNumber(5);
        try{
            $result = User::find($id)->update([
                'password' => bcrypt($password),
            ]);

            if ($result) {
                OauthAccesToken::where('user_id',$id)->delete();
                return $this->successResponse('Password berhasil di reset <<>> passwprd sekarang : '. $password);
            }
            return $this->errorResponse();
        }catch (\Throwable $th){
            return $this->errorResponse($th->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(),[
            'nama' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
        ],[
            'required' => ':attribute harus disi',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->errorResponse($error);
        }
        try{
            $result = User::where('id', $id)->update([
                'nama' => $request->input('nama'),
                'alamat' => $request->input('alamat'),
                'telepon' => $request->input('telepon'),
                'email' => $request->input('email'),
            ]);
            if ($result) {
                return $this->successResponse();
            }
            return $this->errorResponse();
        }catch(\Throwable $th){
            return $this->errorResponse($th->getMessage());
        }
    }

    public function destroy($id){
        $result = User::find($id)->delete();
        if ($result) {
            return $this->successResponse();
        }
        return $this->errorResponse();
    }
}
