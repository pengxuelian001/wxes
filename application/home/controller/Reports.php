<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Customers;
use think\Db;
use think\Cache;
class Reports extends Controller
{
    public function add_Reports(){
        $read = $this->checkRequestData();
        if (is_null($read['openid']) || empty($read['openid'])) {
            $res['success'] = false;
            $res['message'] = "Empty openid";
            return json ($res);

        }elseif(is_null($read['sender']) || empty($read['sender'])){
            $res['success'] = false;
            $res['message'] = "Empty sender";
            return json ($res);
        }elseif(is_null($read['recipients']) || empty($read['recipients'])){
            $res['success'] = false;
            $res['message'] = "Empty recipients";
            return json ($res);
        }
        $result=Db::name('reports')->insert($read);
        if($result){
            Cache::rm('reports');
            $res['success'] = true;
            $res['message'] = "success";
            return json ($res);
        }


    }
    public function select_ReportsList(){

        $arr=Cache::get('reports');
        if(empty($arr)){
            $value3=Db::name('reports')->select();
            Cache::set('reports',$value3,3600);
            return  json_encode($value3);
        }else{
            return  json_encode($arr);
        }
    }
   public function del_Reports(){
       $read = $this->checkRequestData();
       $id=$read['id'];
       $result=Db::name('reports')->where('Id',$id)->delete();
       if($result){
           Cache::rm('reports');
           $res['success'] = true;
           $res['message'] = "success";
           return json ($res);
       }else{
           $res['success'] = false;
           $res['message'] = "delete error";
           return json ($res);
       }
   }
    private function checkRequestData()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods:post');
        $json = file_get_contents("php://input");
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