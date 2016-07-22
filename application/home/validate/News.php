<?php
namespace app\home\validate;

use think\Validate;

class News extends Validate
{
    protected $rule = [
        'title'         => 'require|length:1,255',  // 标题
        'creatusername' => 'require|length:1,30',   // 物品名称
        'content'       => 'require|length:1,65535' // 物品数量
    ];
    protected $message = [
        'title.require'         => '标题必须填写',
        'title.length'          => '标题只能在1~255个字符之间',
        'creatusername.require' => '发表人必须填写',
        'creatusername.length'  => '发表人只能在1~30个字符之间',
        'content.require'       => '内容必须填写',
        'content.length'        => '内容只能在1~65535个字符之间'
    ];
}