<?php
namespace app\controller;

use think\Validate;

class Api
{

    public function __construct() {
        
    }
    
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
     * @param  str|int $_POST['items']        物品列表
     * @return json
     */
    public function addtradeinfo()
    {
        if (isset($_POST['title']) &&
            isset($_POST['trader']) &&
            isset($_POST['onlinetime']) &&
            isset($_POST['tradingplace']) &&
            isset($_POST['tradetype']) &&
            isset($_POST['items'])
        ) {
            // 将物品列表单独取出
            $items = $_POST['items'];
            unset($_POST['items']);
            // 验证交易信息是否符合要求
            $TradeinfoValidate = validate('Tradeinfo');
            if (!$TradeinfoValidate->check($_POST)) {
                return [
                    'status' => 0,
                    'msg' => $TradeinfoValidate->getError()
                ];
            }
            // 验证物品列表是否符合要求
            $TradeiteminfoValidate = validate('Tradeiteminfo');
            $itemnum = count($items);
            for ($i = 0; $i < $itemnum; $i++) { 
                if (!$TradeiteminfoValidate->check($items[$i])) {
                    return [
                        'status' => 0,
                        'msg' => $TradeiteminfoValidate->getError()
                    ];
                }
            }
            // 过滤post数组中的非数据表字段数据并存入数据库
            $TradeinfoModel = model('Tradeinfo');
            $TradeinfoModel->data($_POST);
            $tid = $TradeinfoModel->allowField(true)->save();
            // 判断是否添加成功
            if ($tid) {
                for ($j = 0; $j < $itemnum; $j++) { 
                    $items[$j]['tid'] = $tid;
                }
                $TradeiteminfoModel = model('Tradeiteminfo');
                if (!$TradeiteminfoModel->allowField(true)->saveAll($items)) {
                    return [
                        'status' => 0,
                        'msg' => '交易信息发布失败'
                    ];
                }
            } else {
                return [
                    'status' => 0,
                    'msg' => '交易信息发布失败'
                ];
            }
        } else {
            return [
                'status' => 0,
                'msg' => '传入参数错误'
            ];
        }
        // 数据添加成功
        return [
            'status' => 1,
            'msg' => '交易信息发布成功'
        ];
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
        if (!isset($_GET['lastid']) || !isset($_GET['limit']) || !isset($_GET['type'])) {
            return [
                'status' => 0,
                'msg' => '传入参数错误'
            ];
        }
        // 查询
        /*$_GET['lastid'] = (bool)$_GET['lastid'];
        var_dump($_GET['lastid']);*/
        if ($_GET['lastid'] == 'false') {
            $_GET['lastid'] = false;
        }
        if ($_GET['lastid'] && ($_GET['type'] == 1 || $_GET['type'] == 0)) {
            $dm['tid'] = ['<', $_GET['lastid']];
            $dm['tradetype'] = $_GET['type'];
            $Tradeinfo = $TradeinfoModel->where($dm)->order('tid desc')->limit($_GET['limit'])->select();
        } elseif ($_GET['type'] == 1 || $_GET['type'] == 0) {
            $dm['tradetype'] = $_GET['type'];
            $Tradeinfo = $TradeinfoModel->where($dm)->order('tid desc')->limit($_GET['limit'])->select();
        } elseif ($_GET['lastid']) {
            $dm['tid'] = ['<', $_GET['lastid']];
            $Tradeinfo = $TradeinfoModel->where($dm)->order('tid desc')->limit($_GET['limit'])->select();
        } else {
            $Tradeinfo = $TradeinfoModel->order('tid desc')->limit($_GET['limit'])->select();
        }
        // 获取物品列表
        foreach ($Tradeinfo as $key => $data) {
            $TradeiteminfoModel = model('Tradeiteminfo');
            $Tradeinfo[$key]['items'] = $TradeiteminfoModel->where('tid=' . $data['tid'])->order('iid')->select();
        }
        // 判断是否成功获取
        if (is_array($Tradeinfo)) {
            return [
                'status' => 1,
                'msg' => '成功获取交易信息',
                'data' => $Tradeinfo
            ];
        } else {
            return [
                'status' => 0,
                'msg' => '交易信息获取失败'
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
            // 验证是否符合要求
            $NewsValidate = validate('News');
            if (!$NewsValidate->check($_POST)) {
                return [
                    'status' => 0,
                    'msg' => $NewsValidate->getError()
                ];
            }
            // 过滤post数组中的非数据表字段数据并存入数据库
            $NewsModel->data($_POST);
            $result = $NewsModel->allowField(true)->save();
        } else {
            return [
                'status' => 0,
                'msg' => '传入参数错误'
            ];
        }
        // 判断是否成功添加
        if ($result) {
            return [
                'status' => 1,
                'msg' => '新闻发布成功'
            ];
        } else {
            return [
                'status' => 0,
                'msg' => '新闻发布失败'
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
                'msg' => '传入参数错误'
            ];
        }
        // 判断是否成功获取
        if (is_array($News)) {
            return [
                'status' => 1,
                'msg' => '成功获取新闻',
                'data' => $News
            ];
        } else {
            return [
                'status' => 0,
                'msg' => '新闻获取失败'
            ];
        }
    }

}
