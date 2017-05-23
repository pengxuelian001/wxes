<?php
namespace app\home\controller;
use think\Controller;
use think\Db;

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
           // return null;
        }

        foreach($read as $userInfo => $oneObj) {
            foreach ($oneObj as $key => $value) {
                $data[$key] = $value;
            }
           $result=Db::name('user')->insert($data);
        }
        return json ($result);

    }

    private function checkRequestData()
    {
        $json = file_get_contents("php://input");

        file_put_contents("test.txt", $json);

        if (empty($json)) {
            $res['success'] = false;
            $res['message'] = 'Empty RequestData';
            return json ($res);
            return null;
        }

        $read = json_decode($json,true);
        if (is_null($read)) {
            $res['success'] = false;
            $res['message'] = "json_decode_error";
            return json ($res);
            return null;
        }




        return $read;
    }
}