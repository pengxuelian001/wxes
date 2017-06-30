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
    public function CustomerList(){
        $res=Db::query("select * from(
                             select b.whos,b.detail,a.id as id,a.company,a.charger,a.remark,a.position,a.followUper,a.schedule,a.scale,a.create_time,a.update_time,b.create_time as records_time
                             from rl_customer_info as a
                             left join rl_customer_records as b on a.id=b.customer_id
                              order by b.create_time desc ) as d
                              group by d.id
                              order by d.records_time desc
                    ");
        return $res;
    }
}

