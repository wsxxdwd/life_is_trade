<?php
namespace app\admin\controller;

class Api
{
    /**
     * 查看API版本号:)装个逼而已23333
     * @return json
     */
    public function index()
    {
        return [
            "owner" => "life_is_trade.com",
            "version" => "0.1.1.0"
        ];
    }

    /**
     * 添加第一位
     * @return [type] [description]
     */
    public function addfirstadmin() {

    }

    public function test() {
        $options['salt'] = \helper\Str::random(6);
        $h = \helper\Hash::make($_GET['wd'], 'md5', $options);
        echo $h;
        echo "<br>";
        echo $options['salt'];
        echo "<br>";
        echo strlen($h);
        $sql = "DELETE lit_tradeinfo,lit_tradeiteminfo FROM lit_tradeinfo LEFT JOIN lit_tradeiteminfo ON lit_tradeinfo.tid = lit_tradeiteminfo.tid WHERE lit_tradeinfo.tid = 1";
    }

}
