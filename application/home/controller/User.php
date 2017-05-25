<?php
namespace extend\org\util\PHPexcel;
namespace app\home\controller;
use think\Controller;
use app\home\model\Users;
use think\Db;
use think\Cache;
use think\Loader;
use think\Request;

class User extends Controller
{
    public function add_Users()
    {
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

        $openid=$read["userInfo_openid"]["openid"];

       foreach($read as $userInfo => $oneObj) {
            foreach ($oneObj as $key => $value) {
                $data[$key] = $value;
            }


        }
        $re=Db::name('user')->where("openid='$openid'")->select();

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
           $users =new Users();
           $result=$users->add_list($data);
           if($result){
               $res['success'] = true;
               $res['message'] = "success";
           }
           return json ($res);

       }
    }

    public function ache(){
        $lists=Cache::get('lists');
        if(empty($lists)){
            $lists=Db::table('rl_user')->select();
            Cache::set('lists',$lists,10);
            echo '这是直接读取数据库的数据';
        }else{
            echo '这是缓存的数据';
        }
       // return json ($lists);



    }
    public function upload(){
        $file = request()->file('import');
        $info = $file->validate(['size'=>15678,'ext'=>'xlsx,xls','Filename'=>''])->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
           // echo $info->getFilename();
            $prj=$this->set_Excel($info);

           // echo $info->getsavename();die();
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
       // $file_name = $info->getFilename();//其实很多朋友出错的主要原因就是在这个地方
        $exclePath = $info->getSaveName();  //获取文件名
        $file_name = ROOT_PATH . 'public' . DS . 'uploads' . DS . $exclePath;
       // PRINT_R($file_name);DIE();
        $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if ($extension == 'xlsx') {
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
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
            for ($currentRow = 1; $currentRow <= $allRow; $currentRow++) {
                //从哪列开始，A表示第一列
                for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn++) {
                    //数据坐标
                    $address = $currentColumn . $currentRow;
                    //读取到的数据，保存到数组$arr中
                    $data[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
                }
            }
            echo '<pre>';
            print_r($data);die();
        }


    }







}