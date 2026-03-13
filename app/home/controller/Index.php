<?php
namespace app\home\controller;

use app\home\controller\ComController;
use think\facade\Config;
use think\facade\View;
use think\facade\Db;
use fast\Page;
class Index extends ComController
{
    
    public function index()
    {

        return View::fetch();


    }
    
   
    
}
