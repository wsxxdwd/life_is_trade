<?php
namespace common;

class Strbreaker
{
    /**
     * 简单字符串分词
     * @param  string $str 需要分词的字符串
     * @return array
     */
    public static function strbreaker($str = '') {
        $strlen = mb_strlen($str);
        $strarr = array();
        for ($sublen = 1; $sublen <= $strlen; $sublen++) {
            $aclen = $strlen - ($sublen - 1);
            for ($start = 0; $start < $aclen; $start++) { 
                $strarr[] = mb_substr($str, $start, $sublen);
            }
        }
        return $strarr;
    }
}
