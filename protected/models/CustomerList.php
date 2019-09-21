<?php

class CustomerList extends CListPageModel
{
    public $searchArrears;//

    public $firmList=array();
    public $tableHeardList=array();

    public $id;
    public $client_code;
    public $company_code;
    public $customer_name;
    public $acca_username;
    public $acca_phone;
    public $acca_fun;
    public $acca_lang;
    public $acca_fax;
    public $refer_code;
    public $head_worker;
    public $other_worker;
    public $advance_name;
    public $listing_name;
    public $listing_email;
    public $listing_fax;
    public $new_month;

    public $salesman_id;
    public $staff_id;
    public $group_type;
    public $on_off;
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>Yii::t('several','ID'),
			'firm_name'=>Yii::t('several','in firm'),
			'client_code'=>Yii::t('several','Customer Code'),
			'customer_name'=>Yii::t('several','Customer Name'),
			'company_code'=>Yii::t('several','Company Code'),
			'curr'=>Yii::t('several','Curr'),
			'amt'=>Yii::t('several','Amt'),
			'status_type'=>Yii::t('code','status type'),
			'remarkHtml'=>Yii::t('code','remark html'),
			'firmHtml'=>Yii::t('code','firm html'),

            'acca_username'=>Yii::t('several','accountant username'),
            'acca_phone'=>Yii::t('several','accountant phone'),
            'acca_lang'=>Yii::t('several','accountant lang'),
            'acca_discount'=>Yii::t('several','discount'),
            'on_off'=>Yii::t('several','on off'),
            'acca_remark'=>Yii::t('several','accountant remark'),
            'acca_fun'=>Yii::t('several','method'),
            'acca_fax'=>Yii::t('several','accountant fax'),
            'salesman_id'=>Yii::t('several','salesman'),
            'staff_id'=>Yii::t('several','assign staff'),
            'phone'=>Yii::t('several','phone'),
            'group_type'=>Yii::t('several','group type'),
            'lud'=>Yii::t('several','last time'),
            'payment'=>Yii::t('several','payment'),

            'refer_code'=>Yii::t('several','refer code'),
            'usual_date'=>Yii::t('several','usual date'),
            'head_worker'=>Yii::t('several','head worker'),
            'other_worker'=>Yii::t('several','other worker'),
            'advance_name'=>Yii::t('several','advance name'),
            'listing_name'=>Yii::t('several','listing name'),
            'listing_email'=>Yii::t('several','listing email'),
            'listing_fax'=>Yii::t('several','listing fax'),
            'new_month'=>Yii::t('several','new month'),

            'lbs_month'=>"总公司<br>".Yii::t('several','month num'),
            'other_month'=>Yii::t('several','branch')."<br>".Yii::t('several','month num'),
//group_type  on_off head_worker acca_discount pay_type luu
            'pay_type'=>Yii::t('several','pay type'),
            'luu'=>Yii::t('several','date updated'),
            'remark'=>Yii::t('several','Update Remark'),
		);
	}
    public function rules()
    {
        return array(
            array('attr, pageNum, noOfItem, totalRow, searchField, searchValue, orderField, orderType, searchArrears
            ,id,client_code,customer_name,company_code,acca_username,acca_phone,acca_fax,acca_fun
            ,listing_name,listing_email,listing_fax,new_month,refer_code,head_worker,other_worker,advance_name
            ,salesman_id,staff_id,group_type,on_off
            ','safe',),
        );
    }


    public function getFirmSql(){
        $firm_str = Yii::app()->user->firm();
        $sqlStr="";
        $monthList = UploadExcelForm::getMonth();
        $firmList = array();
        $rows = Yii::app()->db->createCommand()->select()->from("sev_firm")
            ->where("id in ($firm_str)")->order("firm_type desc")->queryAll();
        foreach ($rows as $row){
            $item = $row["id"];
            $sqlStr.=empty($sqlStr)?"":" or ";
            $sqlStr.=" FIND_IN_SET('$item',a.firm_name_id)";

            $this->firmList[$row["id"]] = array("name"=>$row["firm_name"],"type"=>$row["firm_type"]);
            if(empty($row["firm_type"])){
                $firmList[] = $row["id"];
            }
        }
        $this->firmList["branch"] = array("name"=>Yii::t("several","branch"),"type"=>0);//係公司

        $rows = Yii::app()->db->createCommand()->select("b.firm_id,a.amt_gt,a.amt_name")->from("sev_customer_info a")
            ->leftJoin("sev_customer_firm b","a.firm_cus_id = b.id")
            ->where("b.firm_id in ($firm_str)")
            ->group("b.firm_id,a.amt_gt,a.amt_name")->order("a.amt_name asc,a.amt_gt desc")->queryAll();
        foreach ($rows as $row){
            $str = $row["amt_gt"] != 1?"或之前":"";
            $key = $row["amt_gt"].$row["amt_name"];
            //var_dump($key);die();
            $this->tableHeardList[$row["firm_id"]][$key] = $monthList[$row["amt_name"]].$str;
            if(in_array($row["firm_id"],$firmList)){
                $this->tableHeardList["branch"][$key] = $monthList[$row["amt_name"]].$str;
            }
        }

        if(!empty($sqlStr)){
            $sqlStr="((".$sqlStr.") or a.firm_name_id = '')";
        }
        return $sqlStr;
    }

    protected function searchSql(){
        $sql = "";
        $arr = array("id","client_code","customer_name","company_code","acca_username","acca_phone","acca_fax","acca_fun","listing_name","listing_email","listing_fax","new_month","refer_code","head_worker","other_worker","advance_name","salesman_id","staff_id","group_type","on_off");
        foreach ($arr as $value){
            $svalue = str_replace("'","\'",$this->$value);
            if($svalue!==""){
                $sql.=" and ";
                if(in_array($value,array("client_code","customer_name"))){
                    $sql.="c.";
                }elseif (in_array($value,array("company_code"))){
                    $sql.="d.";
                }else{
                    $sql.="a.";
                }
                $sql.=$value." like '%".$svalue."%' ";
            }
        }
        return $sql;
    }

	public function retrieveDataByPage($pageNum=1)
	{
		$suffix = Yii::app()->params['envSuffix'];
		$city = Yii::app()->user->city_allow();
		$firm_str = Yii::app()->user->firm();
		$firmSql = $this->getFirmSql();

		$sql1 = "select a.*,c.client_code,c.customer_name,d.company_code
				from sev_customer a 
				LEFT JOIN sev_company c ON c.id = a.company_id
				LEFT JOIN sev_group d ON d.id = a.group_id
				where $firmSql 
			";
        $sql2 = "select count(*)
				from sev_customer a 
				LEFT JOIN sev_company c ON c.id = a.company_id
				LEFT JOIN sev_group d ON d.id = a.group_id
				where $firmSql 
			";
		$clause = "";
        $clause.=$this->searchSql();
		if (!empty($this->searchField) && !empty($this->searchValue)) {
			$svalue = str_replace("'","\'",$this->searchValue);
			switch ($this->searchField) {
				case 'client_code':
					$clause .= General::getSqlConditionClause('c.client_code',$svalue);
					break;
				case 'customer_name':
					$clause .= General::getSqlConditionClause('c.customer_name',$svalue);
					break;
				case 'company_code':
					$clause .= General::getSqlConditionClause('d.company_code',$svalue);
					break;
			}
		}
		
		$order = "";
		if (!empty($this->orderField)) {
			$order .= " order by ".$this->orderField." ";
			if ($this->orderType=='D') $order .= "desc ";
		}else{
            $order .= " order by a.id desc ";
        }

		$sql = $sql2.$clause;
		$this->totalRow = Yii::app()->db->createCommand($sql)->queryScalar();
		
		$sql = $sql1.$clause.$order;
		$sql = $this->sqlWithPageCriteria($sql, $this->pageNum);
		$records = Yii::app()->db->createCommand($sql)->queryAll();

		$this->attr = array();
		if (count($records) > 0) {
		    $StaffForm = new StaffForm();
		    $langList = FunctionForm::getAllLang();
		    $serverList = FunctionForm::getServiceList();
		    $payTypeList = FunctionForm::getPayList();
			foreach ($records as $k=>$record) {
			    //$color = floatval($record['amt'])>0?"text-danger":"text-primary";
				$this->attr[] = array(
					'id'=>$record['id'],
					'client_code'=>$record['client_code'],
					'customer_name'=>$record['customer_name'],
					'company_code'=>$record['company_code'],
                    'status_type'=>$record['status_type'],
                    'color'=>"",

					'staff_id'=>$StaffForm->getStaffNameToId($record['staff_id']),
					'salesman_id'=>$StaffForm->getStaffNameToId($record['salesman_id']),
					'tableBody'=>$this->getFirmAllAmt($record['id']),
					'payment'=>$record['payment'],
					'group_type'=>empty($record['group_type'])?Yii::t("several","not group"):Yii::t("several","is group"),
					'acca_username'=>$record['acca_username'],
					'acca_phone'=>$record['acca_phone'],
					'acca_fun'=>$record['acca_fun'],
					'on_off'=>$serverList[$record['on_off']],
					'acca_lang'=>$langList[$record['acca_lang']],

                    'acca_fax'=>$record['acca_fax'],
                    'refer_code'=>$record['refer_code'],
                    'usual_date'=>$record['usual_date'],
                    'head_worker'=>$record['head_worker'],
                    'other_worker'=>$record['other_worker'],
                    'advance_name'=>$record['advance_name'],
                    'listing_name'=>$record['listing_name'],
                    'listing_email'=>$record['listing_email'],
                    'listing_fax'=>$record['listing_fax'],
                    'new_month'=>$record['new_month'],
                    'lbs_month'=>$record['lbs_month'],
                    'other_month'=>$record['other_month'],

//group_type  on_off head_worker acca_discount pay_type luu
                    'acca_discount'=>$record['acca_discount'],
                    'pay_type'=>$payTypeList[$record['pay_type']],
                    'luu'=>date("Y-m-d",strtotime($record['lud'])),
                    'remark'=>$this->getRemarkHtml($record['id']),
				);
			}
		}
		$session = Yii::app()->session;
		$session['customer_01'] = $this->getCriteria();
		return true;
	}

	protected function getFirmAllAmt($cus_id){
        $arr = array();
        $rows = Yii::app()->db->createCommand()->select("b.firm_id,a.amt_gt,a.amt_name,a.amt_num")->from("sev_customer_info a")
            ->leftJoin("sev_customer_firm b","a.firm_cus_id = b.id")
            ->where("b.customer_id='$cus_id'")->queryAll();
        foreach ($rows as $row){
            $key = $row["amt_gt"].$row["amt_name"];
            $arr[$row["firm_id"]][$key] = $row["amt_num"];
        }
	    return $arr;
    }

	public function getArrearsList(){
	    return array(
	        "all"=>Yii::t("several","all"),
	        "on arrears"=>Yii::t("several","on arrears"),
	        "off arrears"=>Yii::t("several","off arrears"),
        );
    }

    public function getRemarkHtml($customer_id){
        $html = "";
        $rows = Yii::app()->db->createCommand()->select("a.remark,b.disp_name,a.lcd")->from("sev_remark_list a")
            ->leftJoin("sec_user b","a.lcu = b.username")
            ->where("a.customer_id=:customer_id",array(':customer_id'=>$customer_id))->order("a.lcd desc")->queryAll();

        if($rows){
            $num = 0;
            $bool = count($rows)>1?true:false;
            foreach ($rows as $row){
                $num++;
                if($num!==1){
                    $html.="<br>";
                }
                $serial = $bool?"($num)、":"";
                $html.="<span>$serial ".$row["remark"]." - ".$row["disp_name"]." - ".$row["lcd"]."</span>";
            }
        }
        return $html;
    }

    public function getFirmHtml($firm_id){
        $html = "";
        $rows = Yii::app()->db->createCommand()->select("amt_gt,amt_name,amt_num")->from("sev_customer_info")
            ->where("firm_cus_id=:firm_cus_id and amt_num>0",array(':firm_cus_id'=>$firm_id))->order("amt_name desc")->queryAll();

        if($rows){
            $num = 0;
            $bool = count($rows)>1?true:false;
            $monthList = UploadExcelForm::getMonth();
            foreach ($rows as $row){
                $num++;
                if($num!==1){
                    $html.="<br>";
                }
                $serial = $bool?"($num)、":"";
                $str = $row["amt_gt"] != 1?"或之前":"";
                $html.="<span>$serial ".$monthList[$row["amt_name"]].$str."   ".$row["amt_num"]."</span>";
            }
        }
        return $html;
    }
}