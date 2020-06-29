<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Puser;
use App\Model\Token;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    public function reg(){
        $name=request()->input('name');
        $email=request()->input('email');
        $pwd=request()->input('pwd');
        $pwds=request()->input('pwds');
        if($name==""){
            $data=[
                'errno'=>'50001',
                'msg'=>'用户名不能为空'
            ];
            return $data;
        }
        if($email==""){
            $data=[
                'errno'=>'50002',
                'msg'=>'邮箱不能为空'
            ];
            return $data;
        }
        $pwdlen=strlen($pwd);
        if($pwdlen < 6){
            $data=[
                'errno'=>'50003',
                'msg'=>'密码必须是6值20位'
            ];
            return $data;
        }
        if($pwd !== $pwds){
            $data=[
                'errno'=>'50004',
                'msg'=>'确认密码与密码不符合'
            ];
            return $data;
        }
        $uname=Puser::where('user_name',$name)->first();
        if($uname){
            $data=[
                'errno'=>'50005',
                'msg'=>'用户名已存在'
            ];
            return $data;
        }
        $uemail=Puser::where('user_email',$email)->first();
        if($uemail){
            $data=[
                'errno'=>'50006',
                'msg'=>'邮箱已存在'
            ];
            return $data;
        }
        $data=[
            'user_name'=>$name,
            'user_email'=>$email,
            'user_pwd'=>password_hash($pwd,PASSWORD_BCRYPT ),
            'reg_time'=>time()
        ];
        $res=Puser::insert($data);
        if($res){
            $data=[
                'errno'=>'50000',
                'msg'=>'注册成功'
            ];
            return $data;
        }

    }
    public function login(){
        $name=request()->input('name');
        $pwd=request()->input('pwd');
        if($name==""){
            $data=[
                'errno'=>'50011',
                'msg'=>'账号不能为空'
            ];
            return $data;
        }
        if($pwd==""){
            $data=[
                'errno'=>'50012',
                'msg'=>'密码不能为空'
            ];
            return $data;
        }
        $where = [
            'user_name',$name
        ];
        $user=Puser::where([$where])->first();
        if($user==""){
            $data=[
                'errno'=>'50013',
                'msg'=>'账号或密码错误'
            ];
            return $data;
        }else{
            $pwd=password_verify($pwd,$user['user_pwd']);
            if($pwd=='true'){
                $str=$user->user_id.$name.time();
                $token=substr(md5($str),10,16);
                $datas=[
                    'token'=>$token,
                    'uid'=>$user->user_id
                ];
//                Token::insert($datas);
                Redis::set($token,$user->user_id);
                Redis::expire($token,60*60);
                $data=[
                    'errno'=>'00000',
                    'msg'=>'ok',
                    'token'=>$token
                ];
                return $data;

            }
        }


    }
    public function list(){

        if(isset($_GET['token'])){
            $token=$_GET['token'];
        }else{
            $data=[
                'error'=>'50021',
                'msg'=>'请登录'
            ];
            return $data;
        }

        $uid=Redis::get($token);
        if($uid==""){
            $data=[
                'error'=>'50022',
                'msg'=>'请登录'
            ];
            return $data;
        }else{
            $user=Puser::find($uid);
            echo "欢迎".$user->user_name."来到个人中心";
        }

    }
    public function test(){
        echo phpinfo();
    }
    public function orders(){
//        鉴权

        if(isset($_GET['token'])){
            $token=$_GET['token'];
//            验证token
            $uid=Redis::get($token);
            if($uid){

            }else{
                $data=[
                    'error'=>'50022',
                    'msg'=>'请登录'
                ];
                return $data;
            }
        }else{
            $data=[
                'error'=>'50021',
                'msg'=>'请登录'
            ];
            return $data;
        }

        $aar=[
            '1231u3o1ui',
            'w97189yidw',
            '12863jdsgq'
        ];
        $data=[
            'error'=>'00000',
            'msg'=>'ok',
            'datas' =>[
                "orders"=>$aar
            ]
        ];

        return $data;
    }
}
