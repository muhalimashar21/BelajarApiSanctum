<?php

namespace App\Http\Controllers;

use App\Models\Penelitian;
use App\Models\ProgresFisik;
use App\MOdels\ProgresPengeluaran;
use Illuminate\Http\Request;


class PenelitianController extends Controller
{
    public function indexAdmin()
    {
        $limit =10;
        $currentUser = auth()->user();
        if ($currentUser['id_level'] == 2) {
            $data = $currentUser->penelitian()->paginate($limit);
        }
        $result = $this->paginate($data, $limit);
        if ($result) {
            return $this->successResponse($result);
        }
        return $this->notFoundResponse();
    }

    public function searchAdmin($keyword)
    {
        $limit = 10;
        $currentUser = auth()->user();
        $data = $currentUser->penelitian()
            -> where(function($query) use($keyword){
                $query->orWhere('tahun', 'like', '%'. $keyword . '%')
                       ->orWhere('judul', 'like', '%'. $keyword . '%')
                       ->orWhere('ketua_peneliti','like','%'. $keyword . '%')
                       ->orWhere('kelompok_peneliti', 'like', '%'. $keyword . '%')
                       ->orWhere('sumber_dana','like','%'. $keyword. '%');
            })
            ->paginate($limit);
        $result = $this->paginate($data, $limit);
        if ($result) {
            return $this->successResponse($result);
        }
        return $this->notFoundResponse();
    }

    public function index()
    {
        $limit = 10;
        $data = Penelitian::paginate($limit);
        $result = $this->paginate($data, $limit);
        if ($result) {
            return $this->successResponse($result);
        }
        return $this->notFoundResponse();
    }

    public function show($id)
    {
        $result = Penelitian::with(['dataProgresFisik','dataProgresPengeluaran'])->find($id);
        if ($result) {
            return $this->successResponse($result);
        }
        return $this->notFoundResponse();
    }

    public function search(Request $request)
    {
        $limit =10;
        $data = Penelitian::when($request->tahun, function($query) use ($request){
            $query->where('tahun','like','%'. $request->tahun . '%');
        })
        ->when($request->judul, function($query) use ($request){
            $query->where('judul', 'like', '%'. $request->judul . '%');
        })
        ->when($request->ketua_peneliti, function($query) use ($request){
            $query->where('ketua_peneliti', 'like','%' . $request->ketua_peneliti . '%');
        })
        ->when($request->kelompok_peneliti, function($query) use ($request){
            $query->where('kelompok_peneliti', 'like', '%'. $request->kelompok_peneliti. '%');
        })
        ->when($request->sumber_dana, function($query) use ($request){
            $query->where('sumber_dana','like','%'. $request->sumber_dana. '%');
        })
        ->paginate($limit);
        
        $result = $this->paginate($data, $limit);
        if ($result) {
            return $this->successResponse($result);
        }
        return $this->notFoundResponse();
    }

    public function kategori($kategori)
    {
        $limit = 10;
        switch ($kategori) {
            case 'judul':
                $data = Penelitian::distinct()->get('judul');
                break;
            case 'kelompok_peneliti':
                $data = Penelitian::distinct()->get('kelompok_peneliti');
                break;
            case 'ketua_peneliti':
                $data = Penelitian::distinct()->get('ketua_peneliti');
                break;
            case 'sumber_dana':
                $data = Penelitian::distinct()->get('sumber_dana');
                break;
            case 'tahun':
                $data = Penelitian::distinct()->get('tahun');
                break;

            default:
                $data = false;
                break;
        }

        if ($data) {
            foreach ($data as $key => $value) {
                $result[] = $value[$kategori];
            }
            return $this->successResponse($result);
        }
        return $this->notFoundResponse();
    }

    public function dashboard()
    {
        $data_pengeluaran = number_format(Penelitian::avg('progres_pengeluaran'), 2,".",",");
        $data_fisik = number_format(Penelitian::avg('progres_fisik'),2,".",",");

        $result['progres_pengeluaran'] = $data_pengeluaran;
        $result['progres_fisik'] = $data_fisik;
        if ($result) {
            return $this->successResponse($result);
        }
        return $this->errorResponse();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required',
            'judul' => 'required',
            'ketua_peneliti' => 'required',
            'kelompok_peneliti' => 'required',
            'sumber_dana' => 'required',
            'anggaran' => 'required',
            'progres_fisik' => 'required'
        ],[
            'required' => ':attribute harus diisi',
            'max' => ':attribute harus kosong dari :size',
        ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->errorResponse($error);
        }

        try {
            $anggaran = intval($request->anggaran);
            $total_pengeluaran = array_sum(array_column($request->pengeluaran,'jumlah'));

            if ($total_pengeluaran > $anggaran) {
                return $this->errorResponse('Total pengeluaran melebihi anggaran!');
            }

            $sisa_anggaran = $anggaran - $total_pengeluaran;

            $penelitian = new Penelitian($request->all());
            $penelitian->tahun = strtok($request->tanggal,'-');
            $penelitian->sisa_anggaran = $sisa_anggaran;
            $penelitian->total_pengeluaran = $total_pengeluaran;
            $penelitian->progres_fisik = empty($request->progres_fisik) ? '0.00' : number_format($request->progres_fisik,1, '.',','); 
            $penelitian->progres_pengeluaran = number_format(($total_pengeluaran / anggaran)*100,2, '.',',');
            auth()->user()->penelitian()->save($penelitian);

            $progresFisik = new ProgressFisik();
            $progresFisik->tanggal = $request->tanggal;
            $progresFisik->progres = empty($request->progres_fisik) ? '0.00' :number_format($request->progres_fisik, 2,'.',',');
            $penelitian->dataProgresFisik()->save($progresFisik);

            $pengeluaran = [];
            foreach ($request->pengeluaran as $p) {
                $pengeluaran[] = new ProgresPengeluaran($p);
            }
            $penelitian->dataProgresPengeluaran()->saveMany($pengeluaran);

            return $this->successResponse();
        }catch (\Throwable $th){
            return $this->errorResponse($th->getMessage());
        }
    }
}
