<?php

namespace App\Traits;

trait ApiResponser
{

    protected function successResponse($data = null, $message = 'Sukses')
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'code' => '200',
            'data' => $data,
        ], 200);
    }

    protected function notFoundResponse($message = 'Data tidak ditemukan')
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'code' => '404',
            'data' => null,
        ], 200);
    }

    protected function errorResponse($message = 'Gagal')
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'code' => '400',
            'data' => null,
        ], 200);
    }

    // protected function errorResponse($message = 'Gagal')
    // {
    //     return response()->json([
    //         'status' => false,
    //         'message' => $message,
    //         'code' => '400',
    //         'data' => null,
    //     ], 200);
    // }

    protected function validateResponse($data = null, $message = 'Silahkan Isi Bidang Yang Kosong')
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'code' => '400',
            'data' => $data,
        ], 200);
    }

}
