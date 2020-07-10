<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class Fangshua
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
        $url=$_SERVER['REQUEST_URI'];
        $url_hash=substr(sha1($url),0,10);
        $key="www_".$url_hash;
        $total=Redis::get($key);
        $max=10;
        if($total > $max){ //如果超过10次给出提示并在1分钟后清除$ket;
            Redis::expire($key,10);
            $data=[
                'error'=>"50001",
                'msg'=>"请求过于频繁，请十秒后再来"
            ];
            die(json_encode($data,JSON_UNESCAPED_UNICODE));
        }else{ //否则让$key自增；并在1分钟后删除
            $data=Redis::incr($key);
            Redis::expire($key,60);
        }

        return $next($request);
    }
}
