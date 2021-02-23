<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'level' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
        ], [
            'required' => ':attribute harus diisi',
        ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->errorResponse($error);
        }

        try {
            $level = Level::where('level_id', $request->level)->first();
            if ($level) {
                $password = $this->generateRandNumber(5);
                $username = $this->generateRandNumber(5);

                while (User::where('username', $username)->exists()) {
                    $username = $this->generateRandNumber(5);
                }

                $result = User::create([
                    'nama' => $request->nama,
                    'alamat' => $request->alamat,
                    'telepon' => $request->telepon,
                    'email' => $request->email,
                    'id_level' => $request->level,
                    'username' => $username,
                    'password' => bcrypt($password),
                    'status_code' => '2',
                    'status' => 'aktif',
                ]);
            }
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
        return $this->successResponse('Anda berhasil ditambahkan sebagai ' . ucfirst($level['level_nama']) . ' <<enter>>Username : ' . $username . ' <<enter>>Password : ' . $password);
    }

    public function registerlama(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
        return response()->json([
            'status_code'=> 400,
            'message' => 'Gagal Request'
            ]);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'status' => 200,
            'message' => 'User Created Succesfully'
        ]);
    }

    public function loginAdmin(Request $request){ 
        
        $auth = auth()->attempt(['username' => $request->username,'password' => $request->password]);
        if ($auth) {
            $currentUser = auth()->user();
            $token = $currentUser->createToken('authToken')->plainTextToken;

            $data = [
                'id' => $currentUser->id,
                'level' => $currentUser->id_level,
                'nama' =>$currentUser->nama,
                'token_type' => 'Bearer',
                'token' => $token,  
            ];

            if (($currentUser->id_level == 3)) {
                return $this->errorResponse('Hak akses tidak diijinkan');
            }

            if(($currentUser->status_code == 2)){
                return $this->successResponse($data);
            }

            if (($currentUser->status_code == 3)) {
                return $this->errorResponse('Akun anda sudah tidak aktif, hubungi admin!');
            }

            if (($currentUser->status_code == 4)) {
                return $this->errorResponse('Akun anda di blokir!');
            }

            return $this->errorResponse('Username/Password Salah');
        }
    }
    public function login(Request $request){
        $auth = auth()->attempt(['username' => $request->username, 'password' => $request->password]);
        if ($auth) {
            $currentUser = auth()->user();
            $token = $currentUser->createToken('authToken')->plainTextToken;
            $data = [
                'id' => $currentUser->id,
                'level' => $currentUser->id_level,
                'nama' => $currentUser->nama,
                'token_type' => 'Bearer',
                'token' => $token,
            ];

            if (($currentUser->id_level != 3)) {
                return $this->errorResponse('Hak akses tidak diijinkan!');
            }

            if (($currentUser->status_code == 2)) {
                return $this->successResponse($data);
            }

            if (($currentUser->status_code == 3)) {
                return $this->errorResponse('Akun anda sudah tidak aktif, hubungi admin!');
            }

            if (($currentUser->status_code == 4)) {
                return $this->errorResponse('Akun anda di blokir!');
            }

            return $this->errorResponse('Username/Password Salah!');
        }
        return $this->errorResponse('Username/Password Salah!');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Token Deleted Successfully'
        ]); 
    }
}
