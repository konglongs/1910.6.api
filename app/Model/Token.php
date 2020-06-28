<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    public $table = 'p_token'; //表名
    protected $primaryKey ='id'; //主键
}
