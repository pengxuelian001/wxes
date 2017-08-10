<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\report;
use think\Db;
use think\Cache;
class Reports extends Controller
{
    public function add_Reports(){
        $read = $this->checkRequestData();
        $recive=$read['recive'];
        $copy=array();
        $reci=array();
        if(is_null($read['imgList']) || empty($read['imgList'])){
            foreach($recive as $k=>$vv){
                $types=$recive[$k]['type'];
                if($types==1){
                    $reci[]=$recive[$k]['id'];
                }elseif($types==2){
                    $copy[]=$recive[$k]['id'];
                }
            }
            $data['openid']=$read['openid'];
            $data['title']=$read['title'];
            $data['copy']=implode(',',$copy);
            $data['recipients']=implode(',',$reci);
            $data['position']=$read['position'];
            $data['content']=$read['content'];
            $reciveData['reportId']=Db::name('reports')->insertGetId($data);
            for($j=0;$j<count($recive);$j++){
                $reciveData["reciveId"]=$recive[$j]['id'];
                $reciveData["type"]=$recive[$j]['type'];
                $resl= Db::name('reports_readed')->insertGetId($reciveData);
            }
            if($resl){
                $res['success'] = true;
                $res['message'] = "success";
                return json ($res);
            }
        }
        $img=$read['imgList'];
        foreach($img as $v){
           $da[]=$v;
        }
        foreach($recive as $k=>$vv){
             $types=$recive[$k]['type'];
            if($types==2){
                $copy[]=$recive[$k]['id'];
            }elseif($types==1){
                $reci[]=$recive[$k]['id'];
            }
        }
        $arr=implode(',',$da);
        $data['openid']=$read['openid'];
        $data['title']=$read['title'];
        $data['copy']=implode(',',$copy);
        $data['recipients']=implode(',',$reci);
        $data['imgList']=$arr;
        $data['position']=$read['position'];
        $data['content']=$read['content'];
        $reciveData['reportId']=Db::name('reports')->insertGetId($data);
        for($j=0;$j<count($recive);$j++){
            $reciveData["reciveId"]=$recive[$j]['id'];
            $reciveData["type"]=$recive[$j]['type'];
            $resl= Db::name('reports_readed')->insertGetId($reciveData);
        }
        if($resl){
            $res['success'] = true;
            $res['message'] = "success";
            return json ($res);
        }
    }
    public  function upload(){
        $arr= $this->request->param();
        $openid=$arr['openid'];
        $filepath=$arr['filePath'];
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads'. DS ."$openid",substr($filepath,9));
        if($info){
            $filename = ROOT_PATH . 'public' . DS . 'uploads' . DS . $filepath;

            return json ($filename);
        }


    }
    public function age(){
        $data['username']='pp';
        $data['password']=md5('123');
        $data['age']=12;
        $data['sex']=0;
        $result=Db::connect('db2')->table('user')->insert($data);
        print_R($result);

    }
    public function select_ReportsList(){
//        $type=1;
//        $typeC=1;
//        $openid='oCx4a0aan7yxESfMMBKmYMA_8M50';
        $type=$_GET['type'];
        $typeC=$_GET['typeC'];
        $openid=$_GET['openid'];
    //    发给我的按create_time倒序排序
        if($type==1 && $typeC==1){
            $rep_table=new report();
          $value3=$rep_table->selectRepostlist11($openid);
            return json ($value3);
            //发给我的按update_time倒序排序
        }elseif($type==1 && $typeC==2){
            $rep_table=new report();
            $value3=$rep_table->selectRepostlist12($openid);
            return json ($value3);
            //发给我的,一周以内的按update_time倒序排序
        }elseif($type==1 && $typeC==3){
            $rep_table=new report();
            $value3=$rep_table->selectRepostlist13($openid);
            return json ($value3);
            //发给我的,本月以内的按update_time倒序排序
        }elseif($type==1 && $typeC==4){
            $rep_table=new report();
            $value3=$rep_table->selectRepostlist14($openid);
            return json ($value3);
            //我发出去的按create_time倒序排序
        }elseif($type==2 && $typeC==1){
            $rep_table=new report();
            $value3=$rep_table->selectRepostlist21($openid);
            return json ($value3);
            //我发出去的按 update_time倒序排序
        }elseif($type==2 && $typeC==2){
            $rep_table=new report();
            $value3=$rep_table->selectRepostlist22($openid);
            return json ($value3);
            //我发出去的,一周以内的，按 update_time倒序排序
        }elseif($type==2 && $typeC==3 ){
            $rep_table=new report();
            $value3=$rep_table->selectRepostlist23($openid);
            return json ($value3);
            //我发出去的,本月以内的，按 update_time倒序排序
        }elseif($type==2 && $typeC==4){
            $rep_table=new report();
            $value3=$rep_table->selectRepostlist24($openid);
            return json ($value3);
            //全部，按 create_time倒序排序
        }elseif($type==3 && $typeC==1){
            $rep_table=new report();
            $value3=$rep_table->selectRepostlist31($openid);
            return json ($value3);
            //全部，按 update_time倒序排序
        }elseif($type==3 && $typeC==2){
            $rep_table=new report();
            $value3=$rep_table->selectRepostlist32($openid);
            return json ($value3);
            //全部，一周以内的，按 update_time倒序排序
        }elseif($type==3 && $typeC==3){
            $rep_table=new report();
            $value3=$rep_table->selectRepostlist33($openid);
            return json ($value3);
            //全部，本月以内的，按 update_time倒序排序
        }elseif($type==3 && $typeC==4){
            $rep_table=new report();
            $value3=$rep_table->selectRepostlist34($openid);
            return json ($value3);
        }
    }
    public function Create_text(){
        $arr= $this->request->param();
        $openid=$arr['openid'];
        $type=$arr['type'];
        $file = request()->file('file');
        if($type==1){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'contracts','');
            if($info){
                $exclePath= $info->getSaveName();  //获取文件名
                $filename = ROOT_PATH . 'public' . DS . 'products' . DS .$exclePath;
                //$res['success'] = true;
                //$res['message'] = "success";
                return json ($filename);
            }
        }

    }
    public function select_file(){
     // $type=2;
       $type=$_GET['type'];
        if($type==1){
            $dir="C:/xampp/htdocs/wxes/public/contracts"; //路径
            $handle=opendir($dir.".");
            while (false !== ($file = readdir($handle)))
            {
                if ($file != "." && $file != "..") {
                    //$data[]= $file .':'.$dir;
                    $data[]=mb_convert_encoding($file,"utf-8","gb2312");
                }
            }
            $arr['file']=$data;
            $arr['path']=$dir;
            closedir($handle);
            return json ($arr);
        }elseif($type==2){
            $dir="C:/xampp/htdocs/wxes/public/products"; //路径
            $handle=opendir($dir.".");
            while (false !== ($file = readdir($handle)))
            {
                if ($file != "." && $file != "..") {
                    //$data[]= $file .':'.$dir;
                    $data[]=mb_convert_encoding($file,"utf-8","gb2312");
                }
            }
            $arr['file']=$data;
            $arr['path']=$dir;
            closedir($handle);
            return json ($arr);
        }elseif($type==3) {
            $dir="C:/xampp/htdocs/wxes/public/brochures"; //路径

            $handle=opendir($dir.".");
            while (false !== ($file = readdir($handle)))
            {
                if ($file != "." && $file != "..") {
                   // $data[]= $file .':'.$dir;
                    $data[]=mb_convert_encoding($file,"utf-8","gb2312");
                }
            }
            $arr['file']=$data;
            $arr['path']=$dir;
            closedir($handle);
            return json ($arr);
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
        // file_put_contents("test.txt", $json);
        //print_r($json);die();
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
