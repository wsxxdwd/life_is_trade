<?php
namespace app\home\model;

use think\Model;

class Tradeinfo extends Model
{
    // 开启自动填写时间戳
    protected $autoWriteTimestamp = true;
    // 设定时间戳的字段
    protected $createTime = 'createtime';
    protected $updateTime = 'createtime';
}
