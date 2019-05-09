<?php

/**
 * UserForm class.
 * UserForm is the data structure for keeping
 * user form data. It is used by the 'user' action of 'SiteController'.
 */
class SearchFirmForm extends CFormModel
{
	/* User Fields */
	public $id = 0;
    public $firm_name;
    public $customer_year;

    public $occurrences_num;
	public $collection_num;
	public $collection;
	public $table_body;
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
        return array(
            'id'=>Yii::t('several','ID'),
            'firm_name'=>Yii::t('several','firm name'),

            'occurrences_num'=>Yii::t('several','occurrences number'),
            'collection_num'=>Yii::t('several','collection number'),
            'collection'=>Yii::t('several','For collection'),
            'customer_year'=>Yii::t('several','Customer Year'),
            'table_body'=>Yii::t('several','arrears info'),
        );
	}

    public function onlyReadySearch(){
	    return $this->scenario=='view';
    }

    public function retrieveData($index)
    {
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $rows = Yii::app()->db->createCommand()->select()->from("sev_firm")
            ->where("id=:id", array(':id'=>$index))->queryAll();
        if (count($rows) > 0)
        {
            foreach ($rows as $row)
            {
                $this->id = $row['id'];
                $this->firm_name = $row['firm_name'];
                $this->returnTableBody();
                break;
            }
        }
        return true;
    }

    protected function returnTableBody(){
        $year = $this->customer_year;
        if(!is_numeric($year)){
            $year = date("Y");
        }
        $sql = "SELECT b.id,g.client_code,g.customer_name,b.amt as sum_num 
             FROM sev_customer_firm b
            LEFT JOIN sev_customer a ON a.id = b.customer_id
            LEFT JOIN sev_company g ON g.id = a.company_id
            WHERE a.customer_year = '$year' AND b.firm_id = ".$this->id;
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        if($rows){
            $this->table_body = "";
            $this->collection_num = 0;
            $this->collection = 0;
            $this->occurrences_num = 0;
            foreach ($rows as $row){
                $this->collection += $row["sum_num"];
                if($row["sum_num"]>0){
                    $this->collection_num++;
                    $this->table_body.="<tr class='bg-danger'>";
                }else{
                    $this->table_body.="<tr>";
                }
                $this->occurrences_num++;
                $this->table_body.="<td><a class='glyphicon glyphicon-eye-open' href='".Yii::app()->createUrl("searchCustomer/view",array("index"=>$row["id"]))."' target='_blank'></a></td>";
                $this->table_body.="<td>".$row["id"]."</td>";
                $this->table_body.="<td>".$row["client_code"]."</td>";
                $this->table_body.="<td>".$row["customer_name"]."</td>";
                $this->table_body.="<td>".$row["sum_num"]."</td>";
                $this->table_body.="</tr>";
            }
        }

    }
}
