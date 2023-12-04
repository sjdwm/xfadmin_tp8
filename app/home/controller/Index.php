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

        //app('app\api\controller\erp')->login('erpapi','shejiadong2030');//账号管理API专用账号
        //app('app\api\controller\erp')->login('佘佳栋','SJDxx2030../');
       //return app('app\api\controller\erp')->logout();
       //return app('app\api\controller\erp')->userlist();
       //return app('app\api\controller\erp')->userinfo(899);
       //return app('app\api\controller\erp')->phone();
        //return '小风博客www.hotxf.com 后台：/index.php/admin'.'<a href="../index.php/admin/" class="btn btn_submit J_install_btn">进入后台</a>';

    }
    public function sjd(){
        app('app\api\controller\erp')->login('erpapi','shejiadong2030');//账号管理API专用账号
        app('app\api\controller\erp')->logout();
        return app('app\api\controller\erp')->deptInfo(1);
      
        
       
    }
    public function phone(){

         
            //收集请求数据
            $data = array(
                'sort' => 0,                // 通讯录类型
                'orgsid' => '',                 // 部门ID
                'searchKey' => '',                  // 快速检索条件
                'pagesize' => 0,                // 每页记录数
                'pageindex' => 0,               // 数据页标
                '_rpt_sort' => ''               // 排序字段
            );

            //注：本接口采用V1.0版本方式传参, 参数采用的是id-val键值对数组形式。
            $datas = array();
            foreach ($data as $key => $value) {
                $datas[] = array('id' => $key, 'val' => $value);
            }

            $json_data = array(
                'session' => '******',          // 当接口设置开启了token验证，此字段传鉴权接口返回的Session,  接口参见：http://220.203.29.14:81/webapi/v3/ov1/login   
                'cmdkey' => 'refresh',   // 本接口cmdkey固定传此值 
                'datas' => $datas
            );

            // 执行网络请求
            $url = 'http://220.203.29.14:81/sysa/mobilephone/officemanage/businesscard/list.asp';
            $headers = array('Content-Type: application/json');
            $options = array(
                'http' => array(
                    'header' => $headers,
                    'method' => 'POST',
                    'content' => json_encode($json_data),
                ),
            );
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);

            echo $result;
    }
    public function ss(){
        $ch = curl_init();
     $json = '{session:"E21C3B6D5000C216B68BBA0427716729D0B8E6E54F2DB847D61EA7384538E6FE912B75260F693D4A8A6CA9E822DDE8D065B1DD7845BCE8BFD99BE45A444F307D6854298D61FBFE5AB29D297263EE43475A306702594E9DB9" , datas:[';
     $json .= '  {id:"ord", val:"185"}';      /*数据唯一标识*/
     $json .= ']}';
     curl_setopt($ch, CURLOPT_POST, 1);
     curl_setopt($ch, CURLOPT_URL, 'http://220.203.29.14:81/SYSA/mobilephone/officemanage/businesscard/content.asp');
     curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
     curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/zsml; charset=utf-8',   /*接口规定content-type值必须为application/zsml。*/
          'Content-Length: '.strlen($json))
     );
     ob_start();
     curl_exec($ch);  /*输出返回结果*/
     $b=ob_get_contents();
     ob_end_clean();
     echo $b;
    }
   
    
}
