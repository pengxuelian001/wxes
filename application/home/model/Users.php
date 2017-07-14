<?php 
namespace app\home\model;
use think\Model;
use think\Db;
class Users extends Model
{
    protected $table = 'rl_user';
    public  function select_tableByopenid($openid){
        $list = DB::table($this->table)->where('openid=:openid')->bind(['openid'=>"$openid"])->select();
        return $list;
    }
    public  function select_List(){
        $list = DB::table($this->table)->select();
        return $list;
    }
    public  function add_list($data){
        $list = DB::table($this->table)->insertGetId($data);
        return $list;
    }
    public function updata_user($openid,$arr){
        $list = DB::table($this->table)->where('openid=:openid')->bind(['openid'=>"$openid"])->update($arr);
        return $list;
    }
    public function delelte_user($openid){
        $list = DB::table($this->table)->where('openid=:openid')->bind(['openid'=>"$openid"])->delete();
        return $list;
    }
    public function page(){
        $list = DB::table($this->table)->where('status=:status')->bind(['status'=>1])->paginate(5);
          return $list;
    }
    public function Group_Report(){
        $list = DB::name('department')->field('id ,name ')->group('id')->select();
        return $list;
    }
    public function getReportList($company_id,$group_id){

        $list = DB::table($this->table)->where("company_id=:company_id AND usergroup=:usergroup")->bind(['company_id'=>"$company_id",'usergroup'=>"$group_id"])->select();
        return $list;
    }
    public  function  get_value($openid){
        $list = DB::table($this->table)->where("openid=:openid")->bind(['openid'=>"$openid"])->field('nickName,avatarUrl,company_id,usergroup')->select();

        return $list;
    }
    public  function  get_name($oid){
//        $list= Db::table('rl_user')
//            ->alias('a')
//            ->join('rl_company b','a.company_id=b.id')
//            ->where("openid=:openid")->bind(['openid'=>"$openid"])
//            ->field(' a.openid,a.nickName,a.avatarUrl,b.name as company_name')
//            ->select();
//        $list=Db::query("select a.openid,a.nickName,a.avatarUrl,b.name as company_name from rl_user as a
//                left join rl_company as b on a.company_id=b.id WHERE openid='$oid'");
        $list=Db::query("select a.openid,a.nickName,a.avatarUrl from rl_user as a WHERE openid='$oid'");
        return $list;
    }
}
        
       