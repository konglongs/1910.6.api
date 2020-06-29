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
}
