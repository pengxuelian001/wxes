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
    public function Group_Report(){
        $list= Db::query("SELECT id ,name FROM rl_department GROUP BY id");
        return $list;
    }
    public  function  get_value($openid){
      //  $list = DB::table($this->table)->where('openid',$openid)->select("usergroup,company_id");
        $list=Db::query("select nickName,avatarUrl,company_id,usergroup from rl_user WHERE openid='$openid'");
        return $list;
    }
    public  function  get_name($openid){
        //  $list = DB::table($this->table)->where('openid',$openid)->select("usergroup,company_id");
        $list=Db::query("select a.nickName,a.avatarUrl,b.name as company_name from rl_user as a  left join rl_company as b on a.company_id=b.id WHERE openid='$openid'");
        return $list;
    }
}
        
       