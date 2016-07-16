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
     * @param  string  $_POST['title']                  标题
     * @param  integer $_POST['itemquality']            物品质量
     * @param  string  $_POST['trader']                 交易发起人
     * @param  string  $_POST['onlinetime']             在线时间
     * @param  string  $_POST['tradingplace']           交易地点
     * @param  string  $_POST['tradetype']              交易类型
     * @param  array   $_POST['items']                  物品列表
     * @param  string  $_POST['items'][x]['itemname']   物品名称
     * @param  integer $_POST['items'][x]['itemnum']    物品数量
     * @param  string  $_POST['items'][x]['itemprice']  物品单价
     * @return json
     */
    public function addtradeinfo()
    {
        // 判断所需参数是否全部存在如果有确实则结束
        if (!isset($_POST['title']) ||
            !isset($_POST['trader']) ||
            !isset($_POST['onlinetime']) ||
            !isset($_POST['tradingplace']) ||
            !isset($_POST['tradetype']) ||
            !isset($_POST['items'])) {
            return [
                'status' => 0,
                'msg' => '传入参数错误'
            ];
        }
        // 验证交易信息是否符合要求
        $TradeinfoValidate = validate('Tradeinfo');
        if (!$TradeinfoValidate->check($_POST)) {
            return [
                'status' => 0,
                'msg' => $TradeinfoValidate->getError()
            ];
        }
        // 将物品列表单独取出并验证物品列表是否符合要求
        $TradeiteminfoValidate = validate('Tradeiteminfo');
        $items = $_POST['items'];
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
        $tid = $TradeinfoModel->allowField(true)->save($_POST);
        // 判断是否添加成功
        if ($tid) {
            for ($j = 0; $j < $itemnum; $j++) { 
                $items[$j]['tid'] = $tid;
            }
            $TradeiteminfoModel = model('Tradeiteminfo');
            if ($TradeiteminfoModel->allowField(true)->saveAll($items)) {
                return [
                    'status' => 1,
                    'msg' => '交易信息发布成功'
                ];
            } else {
                // 失败的话便删除所有已经添加的相关联的信息
                $TradeinfoModel->destroy($tid);
                $TradeiteminfoModel->where('tid=' . $tid)->delete();
                return [
                    'status' => 0,
                    'msg' => '物品信息未正确存入'
                ];
            }
        } else {
            return [
                'status' => 0,
                'msg' => '交易信息发布失败'
            ];
        }
    }

    /**
     * 获取交易信息
     * @param  str|int  $_GET['type']   交易类型 0,1,all
     * @param  str|int  $_GET['limit']  每页数量
     * @param  str|int  $_GET['lastid'] 最后获取的ID，相对于前端
     * @param  str|bool $_GET['isnew']  是否为下拉刷新
     * @return json
     */
    public function gettradeinfo()
    {
        // 判断参数是否存在不存在则结束
        if (!isset($_GET['lastid']) || !isset($_GET['limit']) || !isset($_GET['type'])) {
            return [
                'status' => 0,
                'msg' => '传入参数错误'
            ];
        }
        if (isset($_GET['isnew'])) {
            if ($_GET['isnew'] = 'false' || $_GET['isnew'] = false) {
                $exp = '<';
            } else {
                $exp = '>';
            }
        } else {
            $exp = '<';
        }
        // 获取交易信息列表
        $TradeinfoModel = model('Tradeinfo');
        if ($_GET['lastid'] == 'false') {
            $_GET['lastid'] = false;
        }
        if ($_GET['lastid'] && ($_GET['type'] == 1 || $_GET['type'] == 0)) {
            $dm['tid'] = [$exp, $_GET['lastid']];
            $dm['tradetype'] = $_GET['type'];
            $Tradeinfo = $TradeinfoModel->where($dm)->order('tid desc')->limit($_GET['limit'])->select();
        } elseif ($_GET['type'] == 1 || $_GET['type'] == 0) {
            $dm['tradetype'] = $_GET['type'];
            $Tradeinfo = $TradeinfoModel->where($dm)->order('tid desc')->limit($_GET['limit'])->select();
        } elseif ($_GET['lastid']) {
            $dm['tid'] = [$exp, $_GET['lastid']];
            $Tradeinfo = $TradeinfoModel->where($dm)->order('tid desc')->limit($_GET['limit'])->select();
        } else {
            $Tradeinfo = $TradeinfoModel->order('tid desc')->limit($_GET['limit'])->select();
        }
        // 判断是否成功获取交易信息列表如果成功获取便获取物品信息
        if (is_array($Tradeinfo)) {
            // 获取物品列表
            foreach ($Tradeinfo as $key => $data) {
                $TradeiteminfoModel = model('Tradeiteminfo');
                $item = $TradeiteminfoModel->where('tid=' . $data['tid'])->order('iid')->select();
                if (is_array($item)) {
                    $Tradeinfo[$key]['items'] = $item;
                } else {
                   return [
                        'status' => 0,
                        'msg' => '物品信息获取失败'
                    ]; 
                }
            }
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
     * @param  string $_POST['title']         标题
     * @param  string $_POST['creatusername'] 发表人
     * @param  string $_POST['content']       内容
     * @return json
     */
    public function addnew()
    {
        // 判断所需参数是否全部存在如果有确实则结束
        if (!isset($_POST['title']) || !isset($_POST['creatusername']) || !isset($_POST['content'])) {
            return [
                'status' => 0,
                'msg' => '传入参数错误'
            ];
        }
        // 验证是否符合要求
        $NewsValidate = validate('News');
        if (!$NewsValidate->check($_POST)) {
            return [
                'status' => 0,
                'msg' => $NewsValidate->getError()
            ];
        }
        // 过滤post数组中的非数据表字段数据并存入数据库
        $NewsModel = model('News');
        if ($NewsModel->allowField(true)->save($_POST)) {
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
     * @param  str|int $_GET['limit']  每页数量
     * @param  str|int $_GET['lastid'] 页码
     * @return json
     */
    public function getnews()
    {
        // 判断所需参数是否全部存在如果有确实则结束
        if (!isset($_GET['limit']) || !isset($_GET['lastid'])) {
            return [
                'status' => 0,
                'msg' => '传入参数错误'
            ];
        }
        // 获取新闻列表
        $NewsModel = model('News');
        if ($_GET['lastid'] == 'false') {
            $_GET['lastid'] = false;
        }
        if ($_GET['lastid']) {
            $dm['nid'] = ['<', $_GET['lastid']];
            $News = $NewsModel->where($dm)->order('nid desc')->limit($_GET['limit'])->select();
        } else {
            $News = $NewsModel->order('nid desc')->limit($_GET['limit'])->select();
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
