<?php
namespace app\home\model;
use think\Model;
use think\Db;
class Customers extends Model
{
    protected $table = 'rl_customer_info';
    public  function select_table(){
        $list = DB::table($this->table)->select();
        return array($list);
    }
    public  function add_list($data){
        $list = DB::table($this->table)->insertGetId($data);
        return array($list);
    }
    public function page(){
        $list = DB::table($this->table)->where('status',1)->paginate(5);
        return array($list);
    }
}

