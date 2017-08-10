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

        }elseif(is_null($read['theme']) || empty($read['theme'])){
            $res['success'] = false;
            $res['message'] = "Empty theme";
            return json ($res);
        }
        elseif(is_null($read['customer_id']) || empty($read['customer_id'])){
            $res['success'] = false;
            $res['message'] = "Empty customer_id";
            return json ($res);
        }elseif(is_null($read['participants']) || empty($read['participants'])){
            $res['success'] = false;
            $res['message'] = "Empty recipients";
            return json ($res);
        }
        $data['customer_id']=$read['customer_id'];
        $data['create_time']=$read['date'];
        $data['depict']=$read['depict'];
        $data['importance']=$read['importance'];
        $data['openid']=$read['openid'];
        $data['participants']=$read['participants'];
        $data['principal']=$read['principal'];
        $data['theme']=$read['theme'];
        $result=Db::name('task')->insert($data);
        if($result){
            $res['success'] = true;
            $res['message'] = "success";
            return json ($res);
        }


    }
    public function getHttp(){
        $js_code= $_GET['js_code'];
        $url="https://api.weixin.qq.com/sns/jscode2session?appid=wx84f945286d5ad907&secret=df9fe3ebc39d0b4bd30335110bafe393&grant_type=authorization_code&js_code=".$js_code;
        $return = $this->curl_get($url);
        return json ($return);
    }
    public function curl_get($url = "") {
        if (!$url) {
            return false;
        } $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); //设定为不验证证书和host
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $output = json_decode(curl_exec($ch));
        curl_close($ch);
        return $output;
    }
    public function select_TaskList(){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods:get');
       $openid=$_GET['open_id'];
       $start_time=$_GET['start_time'];
       // $openid='olonw0Cb5KgE32C3Y8OW2Ir8YQ0w';
       //$start_time='2017-08-09';
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
//        $id=5;
//        $tpye=1;
        if($tpye==1){
            $result=Db::name('task')->where('Id',$id)->field('done')->select();
            $done=$result[0]['done'];
            if($done==1){
               $result=Db::name('task')->where('id',$id)->setField('done',0);
                if($result){
                    $res['success'] = true;
                    $res['message'] = "update success";
                    return json ($res);
                }else{

                    $res['success'] = false;
                    $res['message'] = "update error";
                    return json ($res);
                }
            }elseif($done==0){
                $result=Db::name('task')->where('id',$id)->setField('done',1);
                if($result){

                    $res['success'] = true;
                    $res['message'] = "update success";
                    return json ($res);
                }else{

                    $res['success'] = false;
                    $res['message'] = "update error";
                    return json ($res);
                }
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