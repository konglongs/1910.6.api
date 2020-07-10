<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(){
        $data="hello world";
        $key="1910";
        $sign=sha1($data.$key);
        echo $data;
        echo "<br>";
        echo $sign;
        echo '<hr>';

        $url='http://www.1910.com/test/secret?data='.$data.'&sign='.$sign;
        echo $url;
    }
    public function secret(){
        $data=$_GET['data'];
        $sign=$_GET['sign'];
        $key="1910";
        $local_sign=sha1($data.$key);
        if($sign == $local_sign){
            echo "验签成功";
        }else{
            echo "验签失败";
        }

    }

    public function www(){
        $key="1910";
        $url = 'http://api.1910.com/api/info';
        $data = 'hello kwl';
        $sign=sha1($data.$key);

        $url = $url.'?data='.$data.'&sign='.$sign;
        //php 发起网络请求
        $response = file_get_contents($url);
        echo $response;

    }
    public function send_data(){
        $url="http://api.1910.com/api/receive?name=kongweilong&age=18";
        $response = file_get_contents($url);
        echo $response;
    }
//    使用post传
    public function post_data(){
        $key="secret";
        $data=[
            "name"=>"kongweilong",
            "age"=>"18"
        ];
        $str=json_encode($data);
        $sign=sha1($str.$key);
        $send_data=[
            "data"=>json_encode($data),
            "sign"=>$sign
        ];
        dump($send_data);

        $url="http://api.1910.com/api/receive_post";
        //实例化
        $ch=curl_init();
//        配置参数
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$send_data);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

//        开启回话（发请求）
        $response=curl_exec($ch);
//        检测错误
        $errno=curl_errno($ch);
        $errmsg=curl_error($ch);
        if($errno > 0){
            echo $errmsg;die;
        }
        curl_close($ch);
        echo $response;

    }
//    public function encrypt1(){
//        $data="天道好轮回，苍天饶过谁";
//        $method="AES-256-CBC";
//        $key="1910api";
//        $iv="kongweilonggogos";
//        $enc_data=openssl_encrypt($data,$method,$key,OPENSSL_RAW_DATA,$iv);
////        echo $enc_data;
//        var_dump($enc_data);echo "<br>";
//
////        解密
//        $dec_data=openssl_decrypt($enc_data,$method,$key,OPENSSL_RAW_DATA,$iv);
//        var_dump($dec_data);
//
//    }

    public function encrypt1(){
        $data="天道好轮回，苍天饶过谁";
        $method="AES-256-CBC";
        $key="1910api";
        $iv="kongweilonggogos";
        $enc_data=openssl_encrypt($data,$method,$key,OPENSSL_RAW_DATA,$iv);
        $sign=sha1($enc_data.$key);
        $datas=[
            'data'=>$enc_data,
            'sign'=>$sign
        ];
        $url="http://api.1910.com/api/encrypt1";
        //实例化
        $ch=curl_init();
//        配置参数
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

//        开启回话（发请求）
        $response=curl_exec($ch);
//        检测错误
        $errno=curl_errno($ch);
        $errmsg=curl_error($ch);
        if($errno > 0){
            echo $errmsg;die;
        }
        curl_close($ch);
        echo $response;


    }
    //非对称加密
    public function resEncrypt1(){
        $data="无情哈拉少";
//        公加密
        $key_content=file_get_contents(storage_path('keys/pub.key'));
        $pub_key=openssl_get_publickey($key_content);
        openssl_public_encrypt($data,$enc_data,$pub_key);
        var_dump($enc_data);echo "<hr>";
        //私解密
        $key_content=file_get_contents(storage_path('keys/priv.key'));
        $pric_key=openssl_get_privatekey($key_content);
        openssl_private_decrypt($enc_data,$dec_data,$pric_key);
        var_dump($dec_data);
    }

    public function aaa(){
        $data="无情哈拉少";
//        用a的公钥加密

        //a的公钥
        $key_content=file_get_contents(storage_path('keys/a_pub.key'));
        //获取公钥k
        $a_pub_key=openssl_get_publickey($key_content);
        openssl_public_encrypt($data,$enc_data,$a_pub_key);
        $data=urlencode(base64_encode($enc_data));
        $url="http://api.1910.com/api/bbb?data=".$data;
        $response = file_get_contents($url);



        $json_arr = json_decode($response,true);
        $enc_datas=base64_decode($json_arr['data']);
        $key_content=file_get_contents(storage_path('keys/b_pub.key'));
        $pric_key=openssl_get_privatekey($key_content);
        openssl_public_decrypt($enc_datas,$dec_data,$key_content);
        echo $dec_data;


    }
    public function rsaSign(){
        $data="孔维龙孔维龙";
        $key_content=file_get_contents(storage_path('keys/a_priv.key'));
        //获取公钥k


        $key = openssl_get_privatekey( file_get_contents( storage_path('keys/a_priv.key') )  );
        openssl_sign($data,$sign,$key);
        $sign_str = urlencode(base64_encode($sign));
        $url = 'http://api.1910.com//api/rsaSign?data='.$data . '&sign='.$sign_str;
        $response = file_get_contents($url);
        echo $response;


    }

    public function abc(){
        echo "111";
    }

}

