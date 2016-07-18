<?php
namespace app\controller;

use think\Controller;
use common\Strbreaker;
use think\Db;
use app\model\Tradeinfo;
use app\model\Tradeiteminfo;

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
                // 获取交易信息
                $Tradeiteminfolen = count($Tradeiteminfo);
                for ($j = 0; $j < $Tradeiteminfolen; $j++) { 
                    $keylist[] = $Tradeiteminfo[$j]['tid'];
                }
                $Tradeinfo = Tradeinfo::order('createtime')->all($keylist);
                // 判断是否成功获取交易信息列表如果成功获取便获取物品信息
                if (is_array($Tradeinfo)) {
                    // 获取物品列表，并计算相似度
                    $volume = array();
                    foreach ($Tradeinfo as $key => $data) {
                        $items = Tradeiteminfo::where('tid=' . $data['tid'])->order('iid')->select();
                        if (is_array($items)) {
                            $Tradeinfo[$key]['items'] = $items;
                            $percent = 0;
                            $Tradeinfo[$key]['percent'] = 0;
                            // 计算相似度
                            foreach ($items as $itemidx => $item) {
                                similar_text($items[$itemidx]['itemname'], $_GET['wd'], $percent);
                                // 只存入更大的值
                                if ($percent > $Tradeinfo[$key]['percent']) {
                                    $Tradeinfo[$key]['percent'] = $percent;
                                    $volume[$key] = $percent;
                                }
                            }
                        } else {
                           $this->assign('Tradeinfo', false);
                           return $this->fetch();
                        }
                    }
                    // 根据相似度排序并输出数据
                    array_multisort($volume, SORT_DESC, $Tradeinfo);
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
