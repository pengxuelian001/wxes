<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Customers;
use app\home\model\Customerrecord;
use think\Db;
use think\Cache;
class Customer extends Controller
{
    public function add_Customer(){
       $read = $this->checkRequestData();
//        $read=array();
//        $read['charger']="111";
//        $read['company']="66";
//        $read['followUper']="11";
//        $read['openid']="oCx4a0aan7yxESfMMBKmYMA_8M50";
//        $read['position']="11";
//        $read['remark']="11";
//        $read['scale']="11";
//        $read['schedule']="11";
        $company_id=$read['company'];
        $customerTable=new Customers();
        $info=$customerTable->slectBycompany($company_id);
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
        }elseif($info){
            $res['success'] = false;
            $res['message'] = "company  exist ";
            return json ($res);
        }
        $data['company']=$read['company'];
        $data['charger']=$read['charger'];
        $data['remark']=$read['remark'];
        $data['position']=$read['position'];
        $data['followUper']=$read['followUper'];
        $data['schedule']=$read['schedule'];
        $data['scale']=$read['scale'];

        $id=$customerTable->add_list($data);
       $arr['customer_id']=$id;
       $arr['user_id']=$read['openid'];
       $arr['detail']=$read['company'];
       $arr['type']=1;
        $recordsTable=new Customerrecord();
        $result=$recordsTable->insert($arr);
        if($result){
            $res['success'] = true;
            $res['message'] = "success";
            return json ($res);
        }


    }
    public function select_Customerlist(){
        $type=$_GET['type'];
        $typeC=$_GET['typeC'];
        $openid=$_GET['openid'];
//            $type=1;
//            $typeC=2;
//        $openid='oCx4a0aan7yxESfMMBKmYMA_8M50';

      if($type==1 && $typeC==1){
            $cus_table= new Customers();
            $resu=$cus_table->CustomerList1($openid);
              return json ($resu);
        }elseif($type==1 && $typeC==2){
          $cus_table= new Customers();
          $resu=$cus_table->CustomerList3();
          return json ($resu);
      }
      elseif($type==2 && $typeC==2){
            $cus_table= new Customers();
            $resu=$cus_table->CustomerList4();
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
                $res['success'] = true;
                $res['message'] = "success";
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