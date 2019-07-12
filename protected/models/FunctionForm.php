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
        $rows = Yii::app()->db->createCommand()->select("id")->from("sev_group")->queryAll();
        if($rows){
            foreach ($rows as $row){
                FunctionForm::refreshGroupOne($row["id"]);
            }
        }
    }

    public function refreshGroupOne($group_id,$num=0){
        if(empty($group_id)){
            return false;
        }
        $salesman_one = array();
        $salesman_one_ts = array();
        $staff_list = Yii::app()->db->createCommand()->select("a.salesman_id,b.staff_name")->from("sev_customer a")
            ->leftJoin("sev_staff b","b.id = a.salesman_id")
            ->where('a.group_id=:group_id',array(':group_id'=>$group_id))->queryAll();
        if($staff_list){
            foreach ($staff_list as $staff){
                $salesman_one[$staff["salesman_id"]] = $staff["salesman_id"];
                $salesman_one_ts[$staff["salesman_id"]] = $staff["staff_name"];
            }
            if(empty($num)) {
                $num = Yii::app()->db->createCommand()->select("COUNT(*) AS num")->from("sev_customer")
                    ->where('group_id=:group_id',array(':group_id'=>$group_id))->queryScalar();
            }
        }
        if(!empty($salesman_one)){
            $salesman_one = implode(",",$salesman_one);
            $salesman_one_ts = implode(",",$salesman_one_ts);
        }else{
            $salesman_one = null;
            $salesman_one_ts = null;
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

    //獲取服務狀態列表
    public function getServiceList(){
        return array(
            //""=>"",
            0=>Yii::t("several","service off"),
            1=>Yii::t("several","service on"),
        );
    }

    //獲取支付类型列表
    public function getPayList(){
        return array(
            //""=>"",
            0=>Yii::t("several","pay no"),
            1=>Yii::t("several","pay yes"),
        );
    }
}
