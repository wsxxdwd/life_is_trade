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
        return [
            "owner" => "life_is_trade.com",
            "version" => "0.01.000"
        ];
    }

    /**
     * 添加交易信息
     * @param  str|int $_POST['title']        标题
     * @param  str|int $_POST['itemname']     物品名称
     * @param  str|int $_POST['itemnum']      物品数量
     * @param  str|int $_POST['itemprice']    物品单价
     * @param  str|int $_POST['itemquality']  物品质量
     * @param  str|int $_POST['trader']       交易发起人
     * @param  str|int $_POST['onlinetime']   在线时间
     * @param  str|int $_POST['tradingplace'] 交易地点
     * @param  str|int $_POST['tradetype']    交易类型
     * @return json
     */
    public function addtradeinfo()
    {
        $TradeinfoModel = model('Tradeinfo');
        if (isset($_POST['title']) &&
            isset($_POST['itemname']) &&
            isset($_POST['itemnum']) &&
            isset($_POST['itemprice']) &&
            isset($_POST['itemquality']) &&
            isset($_POST['trader']) &&
            isset($_POST['onlinetime']) &&
            isset($_POST['tradingplace']) &&
            isset($_POST['tradetype'])) {
            // 过滤post数组中的非数据表字段数据并存入数据库
            $TradeinfoModel->data($_POST);
            $result = $TradeinfoModel->allowField(true)->save();
        } else {
            return [
                'status' => 0,
                'msg' => 'Parameter error'
            ];
        }
        // 判断是否添加成功
        if ($result) {
            return [
                'status' => 1,
                'msg' => 'Successful release'
            ];
        } else {
            return [
                'status' => 0,
                'msg' => 'Release failure'
            ];
        }
    }

    /**
     * 获取交易信息
     * @param  string  $_GET['type']  交易类型 0,1,all
     * @param  str|int $_GET['limit'] 每页数量
     * @param  str|int $_GET['page']  页码
     * @return json
     */
    public function gettradeinfo()
    {
        $TradeinfoModel = model('Tradeinfo');
        // 判断需要获取的类型并且过滤掉未定义的类型,如果是未定义的则为全部类型
        if (!isset($_GET['page']) || !isset($_GET['limit']) || !isset($_GET['type'])) {
            return [
                'status' => 0,
                'msg' => 'Parameter error'
            ];
        } else if ($_GET['type'] == 1 || $_GET['type'] == 0) {
            $Tradeinfo = $TradeinfoModel->where('tradetype=' . $_GET['type'])->order('tid desc')->page($_GET['page'], $_GET['limit'])->select();
        } else {
            $Tradeinfo = $TradeinfoModel->order('tid desc')->page($_GET['page'], $_GET['limit'])->select();
        }
        // 判断是否成功获取
        if (is_array($Tradeinfo)) {
            return [
                'status' => 1,
                'msg' => 'Successful release',
                'data' => $Tradeinfo
            ];
        } else {
            return [
                'status' => 0,
                'msg' => 'Release failure'
            ];
        }
    }

    /**
     * 添加新闻
     * @param  str|int $_POST['title']         标题
     * @param  str|int $_POST['creatusername'] 发表人
     * @param  str|int $_POST['content']       内容
     * @return json
     */
    public function addnew()
    {
        $NewsModel = model('News');
        if (isset($_POST['title']) &&
            isset($_POST['creatusername']) &&
            isset($_POST['content'])) {
            // 过滤post数组中的非数据表字段数据并存入数据库
            $NewsModel->data($_POST);
            $result = $NewsModel->allowField(true)->save();
        } else {
            return [
                'status' => 0,
                'msg' => 'Parameter error'
            ];
        }
        // 判断是否成功添加
        if ($result) {
            return [
                'status' => 1,
                'msg' => 'Successful release'
            ];
        } else {
            return [
                'status' => 0,
                'msg' => 'Release failure'
            ];
        }
    }

    /**
     * 获取新闻
     * @param  str|int $_GET['limit'] 每页数量
     * @param  str|int $_GET['page']  页码
     * @return json
     */
    public function getnews()
    {
        $NewsModel = model('News');
        if (isset($_GET['page']) && isset($_GET['limit'])) {
            $News = $NewsModel->order('nid desc')->page($_GET['page'], $_GET['limit'])->select();
        } else {
            return [
                'status' => 0,
                'msg' => 'Parameter error'
            ];
        }
        // 判断是否成功获取
        if (is_array($News)) {
            return [
                'status' => 1,
                'msg' => 'Successful release',
                'data' => $News
            ];
        } else {
            return [
                'status' => 0,
                'msg' => 'Release failure'
            ];
        }
    }

}
