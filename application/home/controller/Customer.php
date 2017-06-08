<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Customers;
use think\Db;
use think\Cache;
class Customer extends Controller
{
    public function add_Customer(){
        $read = $this->checkRequestData();

        if (is_null($read['scale']) || empty($read['scale'])) {
            $res['success'] = false;
            $res['message'] = "Empty scale";
            return json ($res);

        }elseif(is_null($read['charger']) || empty($read['charger'])){
            $res['success'] = false;
            $res['message'] = "Empty remark";
            return json ($res);
        }elseif(is_null($read['company']) || empty($read['company'])){
            $res['success'] = false;
            $res['message'] = "Empty company";
            return json ($res);
        }elseif(is_null($read['followUper']) || empty($read['followUper'])){
            $res['success'] = false;
            $res['message'] = "Empty followUper";
            return json ($res);
        }
        $Customer =new Customers();
         $result=$Customer->add_list($read);
        if($result){
            Cache::rm('resu');
            $res['success'] = true;
            $res['message'] = "success";
            return json ($res);
        }


    }
    public function select_Customerlist(){
        $resu=Cache::get('resu');
        if(empty($resu)){
            $resu=Db::name('customer_info')->select();
            Cache::set('resu',$resu,3600);
         return  json_encode($resu);
        }else{
            return  json_encode($resu);
        }

    }
    public function del_Customer(){
        $read = $this->checkRequestData();
        $id=$read['id'];
        $result=Db::name('customer_info')->where('Id',$id)->delete();
        if($result){
            Cache::rm('resu');
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