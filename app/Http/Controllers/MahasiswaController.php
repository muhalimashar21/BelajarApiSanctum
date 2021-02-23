<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // public function index()
    // {
    //     $limit = 10;
    //     $currentUser = auth()->user();
    //     if ($currentUser['id_level'] == 2) {
    //         $data = $currentUser->mahasiswa()->paginate($limit);
    //     }
    //     $result = $this->paginate($data, $limit);
    //     if ($result) {
    //         return $this->successResponse($result);
    //     }
    //     return $this->notFoundResponse();
    // }
    
    public function index()
    {
        
        $mahasiswa = Mahasiswa::all();
        if($mahasiswa){
            return response()->json([
                'status' => true,
                'message' => 'Sukses',
                'code' => '200',
                'data' => $mahasiswa,
            ], 200);
        }else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal',
                'code' => '400',
                'data' => null,
            ], 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama' => 'required',
            'kelas' => 'required'
        ],[
            'nama.required' => 'Masukkan Nama',
            'kelas.required' => 'Masukkan Kelas'
        ]
    );

    if($validator->fails()){
        return $this->validateResponse($validator->errors());
    }else{
        $result = Mahasiswa::create([
            'nama' => $request->input('nama'),
            'kelas' => $request->input('kelas'),
        ]);

        if($result){
            return response()->json([
                'status' => true,
                'message' => 'Sukses',
                'code' => '200',
                'data' => $result,
            ], 200);
        }else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal',
                'code' => '400',
                'data' => null,
            ], 200);
        }
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function show(Mahasiswa $mahasiswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function edit(Mahasiswa $mahasiswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::find($id);
        $mahasiswa->nama = $request->nama;
        $mahasiswa->kelas = $request->kelas;
        $mahasiswa->save();
        if($mahasiswa){
            return response()->json([
                'status' => true,
                'message' => 'Sukses',
                'code' => '200',
                'data' => $mahasiswa,
            ], 200);
        }else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal',
                'code' => '400',
                'data' => null,
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Mahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::find($id);
        $mahasiswa->delete();
        if($mahasiswa){
            return response()->json([
                'status' => true,
                'message' => 'Sukses Data Dihapus',
                'code' => '200',
                'data' => $mahasiswa,
            ], 200);
        }else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal',
                'code' => '400',
                'data' => null,
            ], 200);
        }
    }
}
