<?php
namespace extend\PHPexcel;
namespace app\home\controller;
use think\Controller;
use app\home\model\Users;
use think\Db;
use think\Cache;
use think\Loader;

class User extends Controller
{
    public function add_Users(){
        $read = $this->checkRequestData();
        $openid=$read["userInfo_openid"]["openid"];


       foreach($read as $userInfo => $oneObj) {
            foreach ($oneObj as $key => $value) {
                $data[$key] = $value;
            }
        }
        $re=Db::name('user')->where('openid',$openid)->select();

       if($re){
           $count=$re[0]['count'];
           $arr['count']=$count+1;
           $result=Db::name('user')->where("openid='$openid'")->update($arr);
           if($result!=null){
               $res['success'] = true;
               $res['message'] = "update success";
               return json ($res);
           }

        }else{
           $result=Db::name('user')->insert($data);
           if($result){
               Cache::rm('users');
               $res['success'] = true;
               $res['message'] = "success";
               return json ($res);
           }


       }
    }
    public function arraya(){
//        $sites = array
//        (
//            "runoob"=>array
//            (
//                "菜鸟教程",
//                "http://www.runoob.com"
//            ),
//            "google"=>array
//            (
//                "Google 搜索",
//                "http://www.google.com"
//            ),
//            "taobao"=>array
//            (
//                "淘宝",
//                "http://www.taobao.com"
//            )
//        );
        $read=Array
        (
            "userInfo" => Array
            (
                "nickName" => "拉多布拉",
            "gender" => 1,
            "language" => "zh_CN",
            "city" => "Nanchang",
            "province" => "Jiangxi",
            "country" =>"CN" ,
            "avatarUr" => "http://wx.qlogo.cn/mmopen0"
        ),
    "userInfo_openid" => Array
        (
            "session_key"=> "v1U+K7lv+4ByPI+wzudOMA==",
        "expires_in" => 7200,
            "openid" => "oCx4a0aan7yxESfMMBKmYMA_8M50"
        )

    );

       //$read = $this->checkRequestData();
        foreach($read as $k=>$v){
            foreach($v as $kk=>$v){
                $data[$kk]=$v;
            }
        }
        echo '<pre>';
        print_R($data);
       die();
    }
    public function Group_Reports(){
        $openid="oCx4a0aan7yxESfMMBKmYMA_8M50";
        //$openid=$_GET['openid'];
        $user_table= new Users();
        $user=$user_table->get_value($openid);
        $company_id=$user[0]['company_id'];
        $result=$user_table->Group_Report();
       foreach($result as $k=>$v){
           $group_id=$result[$k]['id'];
            $result[$k]['data']= Db::query("SELECT * FROM rl_user where company_id='$company_id'and usergroup='$group_id'");
        }

        return json ($result);
    }

    public function select_UsersList(){
        $user=Cache::get('users');
        if(empty($user)){
            $value1=Db::name('user')->select();
            Cache::set('users',$value1,3600);
            return  json_encode($value1);
        }else{
            return  json_encode($user);
        }
    }
    public  function del_Users(){
        $read = $this->checkRequestData();
        $openid=$read['openid'];
        $result=Db::name('user')->where('openid',$openid)->delete();
        if($result){
            Cache::rm('users');
            $res['success'] = true;
            $res['message'] = "success";
            return json ($res);
        }else{
            $res['success'] = false;
            $res['message'] = "delete error";
            return json ($res);
        }
    }
    public function upload(){
        $file = request()->file('file');
        print_R($file);die();
        $info = $file->validate(['size'=>15678,'ext'=>'jpg,png'])->move(ROOT_PATH . 'public' . DS . 'uploads');

        $filename = $file -> getInfo()['name'];
        if($info){
            //$prj=$this->set_Excel($info);
        }else{

            echo $file->getError();
        }
    }


    public  function  set_Excel($info){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods:get');
        Loader::import('PHPExcel.Classes.PHPExcel');
        Loader::import('PHPExcel.Classes.PHPExcel.IOFactory.PHPExcel_IOFactory');
        Loader::import('PHPExcel.Classes.PHPExcel.Reader.Excel5');
        Loader::import('PHPExcel.Classes.PHPExcel.Reader.Excel2007');
        $exclePath = $info->getSaveName();  //获取文件名
        $file_name = ROOT_PATH . 'public' . DS . 'uploads' . DS . $exclePath;
        $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if ($extension == 'xlsx') {
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
            // $objPHPExcel = $objReader->load($file_name, $encode = 'utf-8');
            $objPHPExcel = $objReader->load(iconv('utf-8','gb2312',$file_name));
        } else if ($extension == 'xls') {
            $objReader = \PHPExcel_IOFactory::createReader('Excel5');
            $objPHPExcel = $objReader->load(iconv('utf-8','gb2312',$file_name));
        }

        if (($objPHPExcel->getSheetCount() == 0)) {
           ECHO '333333';
        } else {
            $currentSheet = $objPHPExcel->getSheet(0);
            //获取总列数
            $allColumn = $currentSheet->getHighestColumn();
            //获取总行数
            $allRow = $currentSheet->getHighestRow();
            //循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
            for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
                //从哪列开始，A表示第一列
                for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn++) {
                    //数据坐标
                    $address = $currentColumn . $currentRow;
                    //读取到的数据，保存到数组$arr中
                    $data[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
                }
            }
            foreach ($data as $key => $val) {
                $arr=array(
                    'send_time' => $val['A'],
                    'read_status' => $val['B'],
                    'themes' => $val['C'],
                    'type' => $val['D'],
                    'text' => $val['E']
                );
            }

            $result=Db::name('message')->insertGetId($arr);

        }


    }
    private function checkRequestData()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods:post');
        $json = file_get_contents("php://input");

        file_put_contents("test.txt", $json);

        if (empty($json)) {
            $res['success'] = false;
            $res['message'] = 'Empty RequestData';
            return json ($res,'json');
        }
        $read = json_decode($json,true);

        if (is_null($read)) {
            $res['success'] = false;
            $res['message'] = "json_decode_error";
            return json ($res);
        }

        return $read;
    }

    public function arr(){
        $json = file_get_contents("php://input");
        //print_r($json);die();
        $jsonstr = (json_encode($json));
        print_r($jsonstr);
    }
    public function index(){
        if(!empty($_FILES['file'])){
            //获取扩展名
            $exename  = $this->getExeName($_FILES['file']['name']);
            if($exename != 'png' && $exename != 'jpg' && $exename != 'gif'){
                exit('不允许的扩展名');
            }
            $imageSavePath = uniqid().'.'.$exename;
            if(move_uploaded_file($_FILES['file']['tmp_name'], $imageSavePath)){
                echo $imageSavePath;
            }
        }
    }

    public function getExeName($fileName){
        $pathinfo      = pathinfo($fileName);
        return strtolower($pathinfo['extension']);
    }







}