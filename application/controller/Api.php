<?php
namespace app\controller;

class Api
{
    
    // 查看API版本号:)装个逼而已23333
    public function index()
    {
        $apiVersion = array("owner"=>"life_is_trade.com","version"=>"0.01.000");
        return json_encode($apiVersion);
    }
}
