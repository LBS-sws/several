<?php

/**
 * UserForm class.
 * UserForm is the data structure for keeping
 * user form data. It is used by the 'user' action of 'SiteController'.
 */
class SearchGroupForm extends CFormModel
{
	/* User Fields */
	public $id = 0;
    public $company_code;
    public $assign_id;
	public $salesman_one_ts;
	public $table_body;
	public $occurrences_num;
	public $arrears_number;
	public $arrears_money;
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
        return array(
            'id'=>Yii::t('several','ID'),
            'company_code'=>Yii::t('several','company Code'),
            'occurrences_num'=>Yii::t('several','occurrences number'),
            'assign_id'=>Yii::t('several','assign staff'),
            'arrears_number'=>Yii::t('several','arrears number'),
            'arrears_money'=>Yii::t('several','arrears money'),
            'salesman_one_ts'=>Yii::t('several','salesman one'),
            'balance'=>Yii::t('several','Balance details'),
            'summary'=>Yii::t('several','Balance summary'),
        );
	}

    public function onlyReadySearch(){
	    return $this->scenario=='view';
    }

    public function retrieveData($index)
    {
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $rows = Yii::app()->db->createCommand()->select()->from("sev_group")
            ->where("id=:id", array(':id'=>$index))->queryAll();
        if (count($rows) > 0)
        {
            foreach ($rows as $row)
            {
                $this->id = $row['id'];
                $this->assign_id = $row['assign_id'];
                $this->company_code = $row['company_code'];
                $this->salesman_one_ts = $row['salesman_one_ts'];
                $this->returnTableBody();
                break;
            }
        }
        return true;
    }

    protected function returnTableBody(){
        $sql = "SELECT g.firm_name,
SUM(b.amt) as arrears_money,
COUNT(b.firm_id) AS occurrences_num,
sum(case when b.amt>0 then 1 else 0 end ) as arrears_number
             FROM sev_customer_firm b
            LEFT JOIN sev_customer a ON a.id = b.customer_id
            LEFT JOIN sev_firm g ON g.id = b.firm_id
            WHERE a.id>0 AND a.group_id = ".$this->id." GROUP BY b.firm_id";
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        if($rows){
            $this->table_body = "";
            $this->arrears_money = 0;
            $this->arrears_number = 0;
            $this->occurrences_num = 0;
            foreach ($rows as $row){
                $this->arrears_money += $row["arrears_money"];
                $this->arrears_number += $row["arrears_number"];
                $this->occurrences_num += $row["occurrences_num"];
                $this->table_body.="<tr>";
                $this->table_body.="<td>".$row["firm_name"]."</td>";
                $this->table_body.="<td>".$row["occurrences_num"]."</td>";
                $this->table_body.="<td>".$row["arrears_number"]."</td>";
                $this->table_body.="<td>".$row["arrears_money"]."</td>";
                $this->table_body.="</tr>";
            }
        }

    }
}
