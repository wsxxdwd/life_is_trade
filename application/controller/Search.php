<?php
namespace app\controller;

use think\Controller;
use common\Strbreaker;
use think\Db;

class Search extends Controller
{
    public function index()
    {
        if (!isset($_GET['wd']) || $_GET['wd'] == '') {
            // 无关键字
            $this->assign('nowd', true);
        } else {
            // 有关键字
            $this->assign('nowd', false);
            // 对关键字进行简单分词
            $strarr = Strbreaker::strbreaker($_GET['wd']);
            // 生成SQL语句，并查询数据
            $strarrlen = count($strarr);
            $condition = '';
            for ($i = 0; $i < $strarrlen; $i++) { 
                if ($i == 0) {
                    $condition = "itemname LIKE '%" . $strarr[$i] . "%' ";
                } else {
                    $condition .= "OR itemname LIKE '%" . $strarr[$i] . "%' ";
                }
            }
            $Tradeiteminfo = Db::query("select * from lit_tradeiteminfo where (" . $condition . ")");
            // 如果找到数据，计算相似度并按照相似度排序
            if (is_array($Tradeiteminfo)) {
                foreach ($Tradeiteminfo as $key => $row) {
                    similar_text($Tradeiteminfo[$key]['itemname'], $_GET['wd'], $Tradeiteminfo[$key]['percent']);
                    $volume[$key]  = $Tradeiteminfo[$key]['percent'];
                }
                array_multisort($volume, SORT_DESC, $Tradeiteminfo);
                // 获取交易信息
                $Tradeiteminfolen = count($Tradeiteminfo);
                for ($j = 0; $j < $Tradeiteminfolen; $j++) { 
                    $keylist[] = $Tradeiteminfo[$j]['tid'];
                }
                $TradeinfoModel = model('Tradeinfo');
                $Tradeinfo = $TradeinfoModel->all($keylist);
                // 判断是否成功获取交易信息列表如果成功获取便获取物品信息
                if (is_array($Tradeinfo)) {
                    // 获取物品列表
                    foreach ($Tradeinfo as $key => $data) {
                        $TradeiteminfoModel = model('Tradeiteminfo');
                        $item = $TradeiteminfoModel->where('tid=' . $data['tid'])->order('iid')->select();
                        if (is_array($item)) {
                            $Tradeinfo[$key]['items'] = $item;
                        } else {
                           $this->assign('Tradeinfo', false);
                           return $this->fetch();
                        }
                    }
                    $this->assign('Tradeinfo', $Tradeinfo);
                } else {
                    $this->assign('Tradeinfo', false);
                }
            } else {
                $this->assign('Tradeinfo', false);
            }
        }
        return $this->fetch();
    }

}
