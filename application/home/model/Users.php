<?php 
namespace app\home\model;
use think\Model;
use think\Db;
class Users extends Model
{
        protected $table = 'rl_user';
        public  function select_table(){
            $list = DB::table($this->table)->select();
            return $list;
        }
         public  function add_list($data){
            $list = DB::table($this->table)->insertGetId($data);
            return $list;
        }
        public function page(){
            $list = DB::table($this->table)->where('status',1)->paginate(5);
              return $list;
        }
}
        
       