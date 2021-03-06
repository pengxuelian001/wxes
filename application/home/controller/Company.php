<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
use think\Cache;
class Company extends Controller
{
    public function add_Company(){
        $read = $this->checkRequestData();

        $name=$read['name'];
        if (is_null($name) || empty($name)) {
            $res['success'] = false;
            $res['message'] = "Empty name";
            return json ($res);

        }elseif(Db::name('company')->where('name',$name)->find()){
            $res['success'] = false;
            $res['message'] = "name error";
            return json ($res);
        }
        $result=Db::name('company')->insert($read);
        if($result){
            $res['success'] = true;
            $res['message'] = "success";
            return json ($res);
        }


    }
    private function checkRequestData()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods:post');
        $json = file_get_contents("php://input");
       // print_R($json);die();
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
        return $read;
    }
}