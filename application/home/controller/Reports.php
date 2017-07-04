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
        $img=$read['imgList'];
        foreach($img as $v){
           $dat[]=$v;

        }
         $arr=implode(',',$dat);
            $data['openid']=$read['openid'];
            $data['title']=$read['title'];
            $data['copy']=$read['copy'];
            $data['recipients']=$read['recipients'];
            $data['imgList']=$arr;
            $data['position']=$read['position'];
            $id=Db::name('reports')->insertGetId($data);
            if($id){
                $res['success'] = true;
                $res['message'] = "success";
                return json ($res);
            }
    }
    public  function upload(){
        $arr= $this->request->param();
        $openid=$arr['openid'];
        $file = request()->file('file');
        $info = $file->move(APP_PATH . 'public' . DS . 'uploads'. DS ."$openid",'');
        if($info){
             $exclePath = $info->getSaveName();  //获取文件名
             $filename = ROOT_PATH . 'public' . DS . 'uploads' . DS . $exclePath;
            print_R($filename);die();
           // return json ($filename);
          //  echo $info->getExtension();
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
        $arr=Cache::get('reports');
        if(empty($arr)){
            $rep_table=new report();
            $value3=$rep_table->selectRepostlist();
            Cache::set('reports',$value3,3600);
            return  json_encode($value3);
        }else{
            Cache::rm('reports');
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