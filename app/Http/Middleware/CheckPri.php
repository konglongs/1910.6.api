<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class CheckPri
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
//        鉴权
        $token=$request->input('token');
        if($token){
            $uid=Redis::get($token);
            if(!$uid){
                $data=[
                    'error'=>'50009',
                    'msg'=>'鉴权失败,请登录'
                ];
                echo json_encode($data,JSON_UNESCAPED_UNICODE);die;
            }
        }else{
            $data=[
                'error'=>'50010',
                'msg'=>'鉴权失败,请登录'
            ];
            echo json_encode($data,JSON_UNESCAPED_UNICODE);die;

        }

        return $next($request);
    }
}
