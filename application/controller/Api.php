<?php
namespace app\controller;

class Api
{
    
    /**
     * 查看API版本号:)装个逼而已23333
     * @return json
     */
    public function index()
    {
        return json_encode([
            "owner" => "life_is_trade.com",
            "version" => "0.01.000"
        ]);
    }

    /**
     * 添加交易信息
     * @return json
     */
    public function addtradeinfo()
    {
        $TradeinfoModel = model('Tradeinfo');
        $TradeinfoModel->data($_POST);
        // 过滤post数组中的非数据表字段数据并存入数据库
        $result = $TradeinfoModel->allowField(true)->save();
        if ($result) {
            return json_encode([
                'status' => '1',
                'msg' => '成功发布交易信息'
            ]);
        } else {
            return json_encode([
                'status' => '0',
                'msg' => '交易信息发布失败'
            ]);
        }
    }

    /**
     * 获取交易信息
     * @return json
     */
    public function gettradeinfo()
    {
        $TradeinfoModel = model('Tradeinfo');
        // 判断需要获取的类型并且过滤掉未定义的类型,如果是未定义的则为全部类型
        if ($_GET['type'] == '1' || $_GET['type'] == '0') {
            $result = $TradeinfoModel->where('type=' . $_GET['type'])->page($_GET['page'], $_GET['limit'])->select();
        } else {
            $result = $TradeinfoModel->page($_GET['page'], $_GET['limit'])->select();
        }
        // 判断是否成功获取
        if ($result) {
            return json_encode([
                'status' => '1',
                'msg' => '成功获取交易信息',
                'data' => $result
            ]);
        } else {
            return json_encode([
                'status' => '0',
                'msg' => '交易信息获取失败'
            ]);
        }
    }

    /**
     * 添加新闻
     * @return json
     */
    public function addnew()
    {
        $NewModel = model('New');
        $NewModel->data($_POST);
        // 过滤post数组中的非数据表字段数据并存入数据库
        $result = $NewModel->allowField(true)->save();
        if ($result) {
            return json_encode([
                'status' => '1',
                'msg' => '成功发布新闻'
            ]);
        } else {
            return json_encode([
                'status' => '0',
                'msg' => '新闻发布失败'
            ]);
        }
    }

    /**
     * 获取新闻
     * @return json
     */
    public function getnews()
    {
        $NewModel = model('New');
        $result = $NewModel->page($_GET['page'], $_GET['limit'])->select();
        // 判断是否成功获取
        if ($result) {
            return json_encode([
                'status' => '1',
                'msg' => '成功获取新闻',
                'data' => $result
            ]);
        } else {
            return json_encode([
                'status' => '0',
                'msg' => '新闻获取失败'
            ]);
        }
    }

}
