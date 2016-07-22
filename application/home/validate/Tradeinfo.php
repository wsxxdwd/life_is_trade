<?php
namespace app\home\validate;

use think\Validate;

class Tradeinfo extends Validate
{
    protected $rule = [
        'title'         => 'require|length:1,255',          // 标题
        'trader'        => 'require|length:1,30',           // 交易人
        'onlinetime'    => 'require|length:1,22',           // 在线时间
        'tradingplace'  => 'require|length:1,100',          // 交易地点
        'tradetype'     => 'require|number|between:0,1'     // 交易类型
    ];
    protected $message = [
        'title.require'             => '标题必须填写',
        'title.length'              => '标题只能在1~255个字符之间',
        'trader.require'            => '交易人必须填写',
        'trader.length'             => '交易人只能在1~30个字符之间',
        'onlinetime.require'        => '物品名称必须存在',
        'onlinetime.length'         => '物品名称只能在1~22个字符之间',
        'tradingplace.require'      => '交易地点必须存在',
        'tradingplace.length'       => '交易地点只能在1~100个字符之间',
        'tradetype.require'         => '交易类型必须填写',
        'tradetype.number'          => '交易类型必须为数字',
        'tradetype.between'         => '交易类型不存在'
    ];
}