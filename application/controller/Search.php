<?php
namespace app\controller;

use think\Controller;

class Search extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

}
