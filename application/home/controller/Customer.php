<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Customers;
use think\Db;
use think\Cache;
class Customer extends Controller
{
    public function add_Customer(){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods:post');
        $json = file_get_contents("php://input");
        file_put_contents("test.txt", $json);
        if (empty($json)) {
            $res['success'] = false;
            $res['message'] = 'Empty RequestData';
            return json ($res);
        }
        $read = json_decode($json,true);
        if (is_null($read)) {
            $res['success'] = false;
            $res['message'] = "json_decode_error";
            return json ($res);

        }
        foreach($read as $userInfo => $oneObj) {
            $Customer =new Customers();
            $result=$Customer->add_list($oneObj);
        }
        if($result){
            $res['success'] = true;
            $res['message'] = "success";
            return json ($res);
        }

    }
    public function select_Customerlist(){
        $result=Cache::get('result');
        if(empty($result)){
            $Customer =new Customers();
            $result=$Customer->select_table();
            Cache::set('result',$result,3600);
            echo  urldecode(json_encode($result));

        }else{
            echo  urldecode(json_encode($result));
        }
    }


}