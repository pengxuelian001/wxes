<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
use think\Cache;
class Task extends Controller
{
    public function add_Task(){
        $read = $this->checkRequestData();
        if (is_null($read['principal']) || empty($read['principal'])) {
            $res['success'] = false;
            $res['message'] = "Empty principal";
            return json ($res);

        }elseif(is_null($read['customer_id']) || empty($read['customer_id'])){
            $res['success'] = false;
            $res['message'] = "Empty customer_id";
            return json ($res);
        }elseif(is_null($read['participants']) || empty($read['participants'])){
            $res['success'] = false;
            $res['message'] = "Empty recipients";
            return json ($res);
        }
        $result=Db::name('task')->insert($read);
        if($result){
            $res['success'] = true;
            $res['message'] = "success";
            return json ($res);
        }


    }
    public function select_TaskList(){
        $cache=Cache::get('cache');
        if(empty($cache)){
            $cache=Db::name('task')->select();
            Cache::set('cache',$cache,3600);
            return  json_encode($cache);
        }else{
            return  json_encode($cache);
            Cache::rm('cache');
        }
    }
    public function del_Task(){
        $read = $this->checkRequestData();
        $id=$read['id'];
        $result=Db::name('task')->where('Id',$id)->delete();
        if($result){
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
            $this->response($res, 'json');
            return null;
        }

        $read = json_decode($json,true);
        if (is_null($read)) {
            $res['success'] = false;
            $res['message'] = "json_decode_error";
            $this->response($res, 'json');
            return null;
        }
        return $read;
    }
}