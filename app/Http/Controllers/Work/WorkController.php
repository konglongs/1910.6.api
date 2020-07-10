<?php

namespace App\Http\Controllers\Work;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Model\Puser;

class workController extends Controller
{
//    非对称加密
    public function aaa(){
        $data="天王盖地虎";
        $key_content=file_get_contents(storage_path('keys/a_pub.key'));
//        公钥加密
        $pub_key=openssl_get_publickey($key_content);
        openssl_public_encrypt($data,$enc_data,$pub_key);
        $enc_datas=urlencode(base64_encode($enc_data));
        $url="http://api.1910.com/work/aaa?data=".$enc_datas;

        $response = file_get_contents($url);

        echo $response;

    }
//    签名
    public function bbb(){
        $data="天王盖地虎";
        $key="918903531";
        $sign=sha1($data.$key);
        $url="http://api.1910.com/work/bbb";
        $url = $url.'?data='.$data.'&sign='.$sign;
        $response = file_get_contents($url);

        echo $response;
    }

    public function ccc(){
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
        }else{ //否则让$key自增；并在10分钟后删除
            $data=Redis::incr($key);
            Redis::expire($key,60);
        }
    }

    public function ddd(){
        echo "这是d方法";
    }

    public function login(){
        $name=request()->input('name');
        $pwd=request()->input('pwd');
        if(empty($name)){
            $data=[
                'errno'=>'50011',
                'msg'=>'账号不能为空'
            ];
            return $data;
        }
        if(empty($pwd)){
            $data=[
                'errno'=>'50011',
                'msg'=>'账号不能为空'
            ];
            return $data;
        }
        $where = [
            'user_name',$name
        ];
        $user=Puser::where([$where])->first();
        if($user){
            $pwd=password_verify($pwd,$user['user_pwd']);
            $token=time().rand(11111,99999);
            if($pwd=='true'){
                $data=[
                    "error"=>"00000",
                    "msg"=>"登录成功",
                    "token"=>$token
                ];
                return $data;

            }
        }

    }
}
