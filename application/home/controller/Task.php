<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
use app\home\model\tasks;
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
        $read['create_time']=date("Y-m-d");
        $result=Db::name('task')->insert($read);
        if($result){
           // Cache::rm($where);
            $res['success'] = true;
            $res['message'] = "success";
            return json ($res);
        }


    }
    public function select_TaskList(){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods:get');
       $openid=$_GET['open_id'];
       $start_time=$_GET['start_time'];
        //$openid='oCx4a0aan7yxESfMMBKmYMA_8M50';
       // $start_time='2017-06-07';
        $str='%Y-%m-%d';
        $ye=  explode('-', $start_time)[0];
        $me=  explode('-', $start_time)[1];
        $var = date("t",strtotime($start_time));
        for($d=1;$d<=$var;$d++){
            $time = $ye.'-'.$me.'-'.$d;
            $task_table=new tasks();
            $rel=$task_table->select_TaskList($openid,$time,$str);
            if($rel){
                $result[$d-1]['success']=true;
                $result[$d-1]['create_time']=$ye.'-'.$me.'-'.$d;
                foreach($rel as $k=>$v){
                    $result[$d-1]['data'][$k]['create_time']=$ye.'-'.$me.'-'.$d;
                    $result[$d-1]['data'][$k]['taskid']= $rel[$k]['Id'];
                    $result[$d-1]['data'][$k]['theme']= $rel[$k]['theme'];
                    $result[$d-1]['data'][$k]['openid']= $rel[$k]['openid'];
                    $result[$d-1]['data'][$k]['done']= $rel[$k]['done'];
                    $result[$d-1]['data'][$k]['customer_id']= $rel[$k]['customer_id'];
                    $result[$d-1]['data'][$k]['principal']= $rel[$k]['principal'];
                    $result[$d-1]['data'][$k]['participants']= $rel[$k]['participants'];
                    $result[$d-1]['data'][$k]['importance']= $rel[$k]['importance'];
                    $result[$d-1]['data'][$k]['depict']= $rel[$k]['depict'];
                }
            }
            else{
                $result[$d-1]['success']=false;
                $result[$d-1]['create_time']=$ye.'-'.$me.'-'.$d;

            }

        }

        return  json_encode($result);
    }
    public function getTask(){
        $day=date("Y-m-d");
       $openid=$_GET['open_id'];
       // $openid='oCx4a0aan7yxESfMMBKmYMA_8M0';
        $arr=Db::name('task')->where("openid='$openid' and create_time='$day'")->select();
        if($arr){
            return  json_encode($arr);
        }else{
            $res['success'] = false;
            $res['message'] = "null";
            return json ($res);
        }

    }
    public function dealTask(){
        $read = $this->checkRequestData();
        $id=$read['taskId'];
        $tpye=$read['type'];
        $delayTime=$read['delayTime'];
        $openid=$read['openId'];
        if($tpye==1){
            $data['update_time']=$delayTime;
            $data['done']=1;
            $result=Db::name('task')->where('Id',$id)->update($data);
            if($result){
              //  Cache::rm($where);
                $res['success'] = true;
                $res['message'] = "update success";
                return json ($res);
            }else{

                $res['success'] = false;
                $res['message'] = "update error";
                return json ($res);
            }
        }elseif($tpye==3){
            $result=Db::name('task')->where('Id',$id)->delete();
            if($result){
              //  Cache::rm('reports');
                $res['success'] = true;
                $res['message'] = "delete success";
                return json ($res);
            }else{
                $res['success'] = false;
                $res['message'] = "delete error";
                return json ($res);
            }
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