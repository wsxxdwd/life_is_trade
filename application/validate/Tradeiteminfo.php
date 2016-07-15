<?php
namespace app\validate;

use think\Validate;

class Tradeiteminfo extends Validate
{
    protected $rule = [
        'itemname'      => 'require|length:1,30',           // 物品名称
        'itemnum'       => 'require|number|between:1,9999', // 物品数量
        'itemprice'     => 'require|length:1,40',           // 物品单价
        'itemquality'   => 'require|number|between:1,100'   // 物品质量
    ];
    protected $message = [
        'itemname.require'      => '物品名称必须填写',
        'itemname.length'       => '物品名称只能在1~30个字符之间',
        'itemnum.require'       => '物品数量必须填写',
        'itemnum.number'        => '物品数量必须填写',
        'itemnum.between'       => '物品数量只能在1~9999之间',
        'itemprice.require'     => '物品单价必须存在',
        'itemprice.length'      => '物品单价只能在1~40个字符之间',
        'itemquality.require'   => '物品质量必须填写',
        'itemquality.number'    => '物品质量必须为数字',
        'itemquality.between'   => '物品质量只能在1~100之间'
    ];
}