<?php
namespace app\home\model;
use think\Model;
use think\Db;
class Customers extends Model
{
    protected $table = 'rl_customer_info';
    public  function select_table(){
        $list = DB::table($this->table)->select();
        return $list;
    }
    public  function add_list($oneObj){
       // print_r($oneObj);die();
        $list = DB::table($this->table)->insert($oneObj);
        return $list;
    }
    public function page(){
        $list = DB::table($this->table)->where('status',1)->paginate(5);
        return $list;
    }
}

