<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Redis;

use Closure;

class AccessBrush
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)

    {
        $request_url=$_SERVER['REQUEST_URI'];
        $url_hash=substr(md5($request_url),5,10);
        $max=5;
        $expire=10;
        $time_last=60;

        $key="access_total_".$url_hash;
        $total=Redis::get($key);
        if($total > $max){
//                设置key的过期时间
            Redis::expire($key,$expire);
//            echo "请求过去频繁，请{$expire}秒后再试";
            $data=[
                'error'=>'50001',
                'msg'=>"请求过去频繁，请{$expire}秒后再试"
            ];
            die(json_encode($data,JSON_UNESCAPED_UNICODE));
//            return response()->json($data);
        }else{
            $total=Redis::incr($key);
            Redis::expire($key,$time_last);



        }


        return $next($request);
    }
}
