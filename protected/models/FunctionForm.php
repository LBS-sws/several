<?php

/**
 * UserForm class.
 * UserForm is the data structure for keeping
 * user form data. It is used by the 'user' action of 'SiteController'.
 */
class FunctionForm extends CFormModel
{
    //手動刷新集團的銷售列表
    public function refreshGroupAll(){
        $year = date("Y");
        $rows = Yii::app()->db->createCommand()->select("a.id,a.group_id, COUNT(b.id) AS num")->from("sev_customer a")
            ->leftJoin("sev_group b","b.id = a.group_id")
            //->where('a.customer_year=:year',array(':year'=>$year)) //暫時不按年份統計
            ->group("b.id")->queryAll();
        if($rows){
            foreach ($rows as $row){
                $this->refreshGroupOne($row["group_id"],$row["num"]);
            }
        }
    }

    public function refreshGroupOne($group_id,$num=null){
        $salesman_one = array();
        $salesman_one_ts = array();
        $staff_list = Yii::app()->db->createCommand()->select("a.salesman_id,b.staff_name")->from("sev_customer a")
            ->leftJoin("sev_staff b","b.id = a.salesman_id")
            ->where('a.group_id=:group_id',array(':group_id'=>$group_id))->queryAll();
        foreach ($staff_list as $staff){
            $salesman_one[] = $staff["salesman_id"];
            $salesman_one_ts[] = $staff["staff_name"];
        }
        if(!empty($salesman_one)){
            $salesman_one = implode(",",$salesman_one);
            $salesman_one_ts = implode(",",$salesman_one_ts);
        }
        if(empty($num)) {
            $num = Yii::app()->db->createCommand()->select("COUNT(group_id) AS num")->from("sev_customer")
                ->where('group_id=:group_id',array(':group_id'=>$group_id))->queryScalar();
        }
        Yii::app()->db->createCommand()->update("sev_group",
            array(
                "occurrences"=>$num,
                "salesman_one"=>$salesman_one,
                "salesman_one_ts"=>$salesman_one_ts,
            ),"id=:id",array(":id"=>$group_id));
    }

    public function startProcess($firm_cus_id){
        $html = "";
        $rows = Yii::app()->db->createCommand()->select("a.remark,a.lcd,b.disp_name")->from("sev_remark_list a")
            ->leftJoin("sec_user b","b.username = a.lcu")->where("a.firm_cus_id=:firm_cus_id",array(":firm_cus_id"=>$firm_cus_id))->order("id desc")->queryAll();
        if($rows){
            foreach ($rows as $row){
                $html.="<tr><td>".$row["lcd"]."</td>";
                $html.="<td>".$row["remark"]."</td>";
                $html.="<td>".$row["disp_name"]."</td></tr>";
            }
        }
        if(empty($html)){
            $html='<td colspan="3">没有记录</td>';
        }
        return $html;
    }

    //獲取語言列表
    public function getAllLang(){
        return array(
            ""=>"",
            "zh_cn"=>Yii::t("several","zh_cn"),
            "zh_tw"=>Yii::t("several","zh_tw"),
            "en_us"=>Yii::t("several","en_us")
        );
    }
}
