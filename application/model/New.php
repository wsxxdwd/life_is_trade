<?php
namespace app\model;

use think\Model;

class New extends Model
{
    // 开启自动填写时间戳
    protected $autoWriteTimestamp = true;
    // 设定时间戳的字段
    protected $createTime = 'createtime';
}
