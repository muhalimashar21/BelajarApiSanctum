<?php

namespace App\Http\Controllers;
use App\Traits\ApiResponser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use ApiResponser;

    public function generateRand($length)
    {
        return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
    }

    public function generateRandNumber($length)
    {
        return substr(str_shuffle(str_repeat($x = '0123456789', ceil($length / strlen($x)))), 1, $length);
    }

    public function paginate($data, $limit){
        if (!empty($data->items())) {
            $pageCount = (int) ceil($data->total() / $limit);
            $result['page_data'] = $data->items();
            $result['page_detail']['total'] = $data->total();
            $result['page_detail']['perPage'] = $data->perPage();
            $result['page_detail']['pageCount'] = $pageCount;
            $result['page_detail']['currentPage'] = $data->currentPage();
            $result['page_detail']['next'] = $data->nextPageUrl() == null ? null : $data->currentPage() + 1;
            $result['page_detail']['previous'] = $data->previousPageUrl() == null ? null : $data->currentPage() - 1;
            return $result;
        }
        return false;
    }
}
