<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Puser extends Model
{
    public $table = 'p_user'; //表名
    protected $primaryKey ='user_id'; //主键
    public $timestamps=false; // 关闭时间戳
    protected $fillable = ['user_name','user_email','user_pwd','reg_time','last_login','last_ip'];
}
