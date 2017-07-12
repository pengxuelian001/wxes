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
        $list = DB::table($this->table)->insertGetId($oneObj);
        return $list;
    }
    public function slectBycompany($company_id){
        $list = DB::table($this->table)->where('company=:company')->bind(['company'=>"$company_id"])->select();
        return $list;
    }

    public function CustomerList1($openid){
        $res=Db::query("
                        SELECT DISTINCT
                            (t2.id),
                            t2.detail,
                            t2.company,
                            t2.charger,
                            t2.remark,
                            t2. SCHEDULE,
                            t2.scale,
                            t2.position,
                            t2.create_time,
                            t2.update_time
                        FROM
                            (
                                SELECT
                                    b.detail,
                                    a.id AS id,
                                    a.company,
                                    a.charger,
                                    a.remark,
                                    a. SCHEDULE,
                                    a.scale,
                                    a.position,
                                    date_format(a.create_time, '%Y-%m-%d') AS create_time,
                                    date_format(a.update_time, '%Y-%m-%d') AS update_time
                                FROM
                                    rl_customer_info AS a
                                LEFT JOIN rl_customer_records AS b ON a.id = b.customer_id
                                LEFT JOIN rl_user AS c ON a.charger = c.id
                                WHERE c.openid='$openid'
                                GROUP BY
                                    a.id
                                UNION ALL
                                    SELECT
                                        a.detail,
                                        c.id AS id,
                                        c.company,
                                        c.charger,
                                        c.remark,
                                        c. SCHEDULE,
                                        c.scale,
                                        c.position,
                                        date_format(c.create_time, '%Y-%m-%d') AS create_time,
                                        date_format(c.update_time, '%Y-%m-%d') AS update_time
                                    FROM
                                        rl_customer_records AS a
                                    LEFT JOIN rl_user AS b ON a.user_id = b.openid
                                    LEFT JOIN rl_customer_info AS c ON a.customer_id = c.id
                                    WHERE  b.openid='$openid'
                                    GROUP BY
                                        c.id
                            ) AS t2
                        ORDER BY
                            t2.create_time DESC
                     ");

        return $res;
    }
    public function CustomerList2($openid){
        $res=Db::query("
                        SELECT DISTINCT
                            (t2.id),
                            t2.detail,
                            t2.company,
                            t2.charger,
                            t2.remark,
                            t2. SCHEDULE,
                            t2.scale,
                            t2.position,
                            t2.create_time,
                            t2.update_time
                        FROM
                            (
                                SELECT
                                    b.detail,
                                    a.id AS id,
                                    a.company,
                                    a.charger,
                                    a.remark,
                                    a. SCHEDULE,
                                    a.scale,
                                    a.position,
                                    date_format(a.create_time, '%Y-%m-%d') AS create_time,
                                    date_format(a.update_time, '%Y-%m-%d') AS update_time
                                FROM
                                    rl_customer_info AS a
                                LEFT JOIN rl_customer_records AS b ON a.id = b.customer_id
                                LEFT JOIN rl_user AS c ON a.charger = c.id
                                WHERE c.openid='$openid'
                                GROUP BY
                                    a.id
                                UNION ALL
                                    SELECT
                                        a.detail,
                                        c.id AS id,
                                        c.company,
                                        c.charger,
                                        c.remark,
                                        c. SCHEDULE,
                                        c.scale,
                                        c.position,
                                        date_format(c.create_time, '%Y-%m-%d') AS create_time,
                                        date_format(c.update_time, '%Y-%m-%d') AS update_time
                                    FROM
                                        rl_customer_records AS a
                                    LEFT JOIN rl_user AS b ON a.user_id = b.openid
                                    LEFT JOIN rl_customer_info AS c ON a.customer_id = c.id
                                    WHERE  b.openid='$openid'
                                    GROUP BY
                                        c.id
                            ) AS t2
                        ORDER BY
                            t2.update_time DESC
                     ");
        return $res;

    }
    public function CustomerList3()
    {
        $res = Db::query(" select * from(
                             select b.detail,a.id as id,a.company,a.charger,a.remark,a.position,a.followUper,a.schedule,a.scale,date_format(a.create_time,'%Y-%m-%d') as create_time,date_format(a.update_time,'%Y-%m-%d') as update_time
                             from rl_customer_info as a
                             left join rl_customer_records as b on a.id=b.customer_id
                              order by b.create_time desc ) as d
                              group by d.id
                              order by d.create_time desc");
        return $res;
    }
    public function CustomerList4()
    {
        $res = Db::query(" select * from(
                             select b.detail,a.id as id,a.company,a.charger,a.remark,a.position,a.followUper,a.schedule,a.scale,date_format(a.create_time,'%Y-%m-%d') as create_time,date_format(a.update_time,'%Y-%m-%d') as update_time
                             from rl_customer_info as a
                             left join rl_customer_records as b on a.id=b.customer_id
                              order by b.create_time desc ) as d
                              group by d.id
                              order by d.update_time desc");
        return $res;
    }
}

