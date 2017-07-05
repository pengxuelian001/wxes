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
//        $type=$_GET['type'];
//        $typeC=$_GET['typeC'];
//        $openid=$_GET['openid'];
            $type=1;
            $typeC=2;
            $openid='oCx4a0aan7yxESfMMBKmYMA_8M50';
      if($type==1 && $typeC==1){
            $cus_table= new Customers();
            $resu=$cus_table->CustomerList1($openid);
          print_R($resu);die();
              return json ($resu);
        }elseif($type==1 && $typeC==2){
          $cus_table= new Customers();
          $resu=$cus_table->CustomerList3();
          return json ($resu);
      }
      elseif($type==2 && $typeC==2){
            $cus_table= new Customers();
            $resu=$cus_table->CustomerList4($openid);
              return json ($resu);
        }elseif($type==2 && $typeC==1){
          $cus_table= new Customers();
          $resu=$cus_table->CustomerList2($openid);
          return json ($resu);
      }

    }
    public function del_Customer(){
        $read = $this->checkRequestData();
        $id=$read['id'];
        //$id=1;
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
    public function  postUpdateCustomer(){
       $read = $this->checkRequestData();
        $id=$read['id'];
        $data['company']=$read['company'];
        $data['charger']=$read['charger'];
        $data['remark']=$read['remark'];
        $data['position']=$read['position'];
        $data['followUper']=$read['followUper'];
        $data['schedule']=$read['schedule'];
        $data['update_time']=date('Y-m-d H:i:s');
        $data['scale']=$read['scale'];

        $result=Db::name('customer_info')->where('Id',$id)->find();
        if($result){
            $re=Db::name('customer_info')->where('Id',$id)->update($data);
            if($re){
                Cache::rm('resu');
                $res['success'] = true;
                $res['message'] = "success";
                return json ($res);
            }
        }else{

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