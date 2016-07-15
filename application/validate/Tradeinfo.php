<?php
namespace app\validate;

use think\Validate;

class Tradeinfo extends Validate
{
    protected $rule = [
        'title'         => 'require|length:1,255',          // 标题
        'itemname'      => 'require|length:1,30',           // 物品名称
        'itemnum'       => 'require|number|between:1,9999', // 物品数量
        'itemprice'     => 'require|length:1,20',           // 物品单价
        'itemquality'   => 'require|number|between:1,100',  // 物品质量
        'trader'        => 'require|length:1,30',           // 交易人
        'onlinetime'    => 'require|length:1,22',           // 在线时间
        'tradingplace'  => 'require|length:1,100',          // 交易地点
        'tradetype'     => 'require|number|between:0,1'     // 交易类型
    ];
    protected $message = [
        'title.require'             => '标题必须填写',
        'title.length'              => '标题只能在1~255个字符之间',
        'itemname.require'          => '物品名称必须填写',
        'itemname.length'           => '物品名称只能在1~30个字符之间',
        'itemnum.require'           => '物品数量必须填写',
        'itemnum.number'            => '物品数量必须为数字',
        'itemnum.between'           => '物品数量只能在1~9999之间',
        'itemprice.require'         => '物品单价必须填写',
        'itemprice.checkItemPrice'  => '物品单价格式错误',
        'itemquality.require'       => '物品质量必须填写',
        'itemquality.number'        => '物品质量必须为数字',
        'itemquality.between'       => '物品质量只能在1~100之间',
        'trader.require'            => '交易人必须填写',
        'trader.between'            => '交易人只能在1~30个字符之间',
        'onlinetime.require'        => '物品名称必须存在',
        'onlinetime.between'        => '物品名称只能在1~22个字符之间',
        'tradingplace.require'      => '交易地点必须存在',
        'tradingplace.between'      => '交易地点只能在1~100个字符之间',
        'tradetype.require'         => '交易类型必须填写',
        'tradetype.number'          => '交易类型必须为数字',
        'tradetype.between'         => '交易类型不存在'
    ];
}