<?php
namespace app\admin\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        if (!session('admin')) {
            return $this->fetch('login');
        }
        return $this->fetch();
    }

}
