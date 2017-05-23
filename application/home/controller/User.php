<?php
namespace app\home\controller;
use think\Controller;
use think\Db;

class User extends Controller
{
    public function add_Users()
    {
       // echo 11111;
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods:post');
        $data['openid']='1212';
        $data['nickname']='拉多布拉';
        $data['avatarUrl']='1111';
        $data['country']='CN';
        $data['city']='Nanchang';
        $data['language']="zh_CN";
        $result=Db::name('user')->insertGetId($data);
       // echo '<pre>';
          return json ($result);

    }
}