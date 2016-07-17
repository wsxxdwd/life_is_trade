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
            $Tradeiteminfolen = count($Tradeiteminfo);
            // 如果找到数据，计算相似度并按照相似度排序
            if (is_array($Tradeiteminfo)) {
                foreach ($Tradeiteminfo as $key => $row) {
                    similar_text($Tradeiteminfo[$key]['itemname'], $_GET['wd'], $Tradeiteminfo[$key]['percent']);
                    $volume[$key]  = $Tradeiteminfo[$key]['percent'];
                }
                array_multisort($volume, SORT_DESC, $Tradeiteminfo);
                // 获取交易信息
                for ($j = 0; $j < $Tradeiteminfolen; $j++) { 
                    $keylist[] = $Tradeiteminfo[$j]['tid'];
                }
                $TradeinfoModel = model('Tradeinfo');
                $Tradeinfo = $TradeinfoModel->all($keylist);
                var_dump($Tradeinfo);
            } else {
                $this->assign('Tradeinfo', false);
            }
            $this->assign('Tradeiteminfo',$Tradeiteminfo);
        }
        return $this->fetch();
    }

}
