<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller
{
    public function a(){
        $request_url=$_SERVER['REQUEST_URI'];
        $url_hash=substr(md5($request_url),5,10);
        $key="access_total_".$url_hash;
        $expire=10;
        $total=Redis::get($key);
        if($total > 10){
                echo "请求过去频繁，请{$expire}秒后再试";
//                设置key的过期时间
                Redis::expire($key,$expire);
        }else{
            $total=Redis::incr($key);
            echo "当前访问次数为".$total;

        }
    }
    public function b(){
        echo "b方法";

    }
}
