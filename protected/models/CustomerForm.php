<?php

/**
 * UserForm class.
 * UserForm is the data structure for keeping
 * user form data. It is used by the 'user' action of 'SiteController'.
 */
class CustomerForm extends CFormModel
{
	/* User Fields */
	public $id = 0;
	public $client_code;
	public $customer_code;
	public $customer_name;
	public $customer_year;
	public $company_code;
	public $customer_id;
	public $firm_id;
	public $curr;
	public $amt;
	public $lcd;

	public $acca_username;
	public $acca_phone;
	public $acca_remark;
	public $acca_fun;
	public $acca_lang;
	public $acca_discount;
	public $salesman_id;
	public $staff_id;
	public $lud;


	public $info_arr=array();
	protected $validateMonth;
	public $remark;
	public $remark_list;



    public $no_of_attm = array(
        'cust'=>0
    );
    public $docType = 'CUST';
    public $docMasterId = array(
        'cust'=>0
    );
    public $files;
    public $removeFileId = array(
        'cust'=>0
    );
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
        return array(
            'id'=>Yii::t('several','ID'),
            'client_code'=>Yii::t('several','Customer Code'),
            'customer_code'=>Yii::t('several','Customer Code'),
            'customer_name'=>Yii::t('several','Customer Name'),
            'customer_year'=>Yii::t('several','Customer Year'),
            'company_code'=>Yii::t('several','Company Code'),

            'acca_username'=>Yii::t('several','accountant username'),
            'acca_phone'=>Yii::t('several','accountant phone'),
            'acca_lang'=>Yii::t('several','accountant lang'),
            'acca_discount'=>Yii::t('several','discount'),
            'acca_remark'=>Yii::t('several','accountant remark'),
            'acca_fun'=>Yii::t('several','method'),
            'salesman_id'=>Yii::t('several','salesman'),
            'staff_id'=>Yii::t('several','assign staff'),
            'phone'=>Yii::t('several','phone'),
            'lud'=>Yii::t('several','last time'),

            'curr'=>Yii::t('several','Curr'),
            'amt'=>Yii::t('several','Amt'),
            'remark'=>Yii::t('several','Update Remark'),
            'info_arr'=>Yii::t('several','Info Arr'),
        );
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			//array('id, position, leave_reason, remarks, email, staff_type, leader','safe'),
            array('id, customer_id, remark, firm_id, client_code, customer_name, customer_year, company_code, info_arr
            ,acca_username,acca_phone,acca_lang,acca_discount,acca_remark,acca_fun,salesman_id,staff_id,lud','safe'),
			array('remark','required'),
			array('info_arr','validateInfoArr'),
            array('files, removeFileId, docMasterId, no_of_attm','safe'),
		);
	}

	public function validateInfoArr($attribute, $params){
	    $infoList = &$this->info_arr;
	    //var_dump($infoList);
        $monthList = UploadExcelForm::getMonth();
	    foreach ($infoList as $key =>$item){
            if(!empty($item["list"])){
                $infoArr = $item["list"];
                $infoList[$key]["amt"] = 0;
                $this->validateMonth = array();
                foreach ($infoArr as $list){
                    //var_dump($list);die();
                    if (empty($list["amt_name"])&&$list["amt_name"] === ""){
                        $message = Yii::t('several','Amt Name'). "不能為空";
                        $this->addError($attribute,$message);
                        return false;
                    }
                    if (!key_exists($list["amt_name"],$monthList)){
                        $message = Yii::t('several','Amt Name'). "格式不正確";
                        $this->addError($attribute,$message);
                        return false;
                    }
                    if (!key_exists($list["amt_gt"],$this->getGtOrEgt())){
                        $message = Yii::t('several','Amt Name'). "格式不正確";
                        $this->addError($attribute,$message);
                        return false;
                    }
                    if ($list["amt_num"]===""){
                        $message = Yii::t('several','Amt Num'). "不能為空";
                        $this->addError($attribute,$message);
                        return false;
                    }
                    if (!is_numeric($list["amt_num"])){
                        $message = Yii::t('several','Amt Num'). "只能為數字";
                        $this->addError($attribute,$message);
                        return false;
                    }
                    if (floatval($list["amt_num"])<0){
                        $message = Yii::t('several','Amt Num'). "不能小於0";
                        $this->addError($attribute,$message);
                        return false;
                    }
                    if(in_array($list["amt_name"],$this->validateMonth)){
                        $message = Yii::t('several','Amt Name'). "不能重复";
                        $this->addError($attribute,$message);
                        return false;
                    }
                    $this->validateMonth[]=$list["amt_name"];
                    $infoList[$key]["amt"]+=floatval($list["amt_num"]);
                }
            }
        }
        //var_dump($this->info_arr);
    }

    public function onlyReadySearch(){
	    return $this->scenario=='view';
    }

    protected function getGtOrEgt(){
        return array(0=>Yii::t("several","lt"),1=>Yii::t("several","eq"));
    }

    public function tBodyTdHtml($arr=array()){
        if(empty($arr)){
            $arr = array("firm_id" =>":firm_id","key" =>":key","amt_name" =>"","amt_gt" =>0,"amt_num" =>"","bgColor" =>"","readonly" =>false);
        }
        $className = "CustomerForm";
        $html = '<tr data-key="'.$arr["key"].'" class="'.$arr["bgColor"].'">';
        $html .= '<td><div class="input-group">';
        $html.='<div class="input-group-btn">'.TbHtml::dropDownList($className."[info_arr][".$arr["firm_id"]."][list][".$arr["key"]."][amt_gt]",$arr["amt_gt"],$this->getGtOrEgt(),array("class"=>"form-control","readonly"=>$arr["readonly"],"style"=>"width:80px"))."</div>";
        $html.=TbHtml::dropDownList($className."[info_arr][".$arr["firm_id"]."][list][".$arr["key"]."][amt_name]",$arr["amt_name"],UploadExcelForm::getMonth(),array("class"=>"form-control","readonly"=>$arr["readonly"]));
        $html.="</div></td>";
        $html .= "<td>".TbHtml::numberField($className."[info_arr][".$arr["firm_id"]."][list][".$arr["key"]."][amt_num]",$arr["amt_num"],array("class"=>"form-control changeAmt","min"=>0,"readonly"=>$arr["readonly"]))."</td>";
        if(!$arr["readonly"]){
            $html .= "<td>".TbHtml::button(Yii::t('misc','Delete'),array("class"=>"delWage btn btn-warning"))."</td>";
        }
        $html .= "</tr>";
	    return $html;
    }

    public function printInfoBody($arr){
        $className = "CustomerForm";
        $firm_id = $arr["firm_id"];
        $bool = $this->onlyReadySearch();
        $html = '<p>&nbsp;</p><div class="form-group">';
        $html .=TbHtml::label(Yii::t("several","Clients firm"),"",array('class'=>"col-sm-2 control-label"));
        $html .='<div class="col-sm-5">';
        $html .=TbHtml::textField($className."[info_arr][$firm_id][firm_name]",$arr["firm_name"],array('readonly'=>(true)));
        $html .='</div></div>';
        $html .= '<div class="form-group">';
        $html .=TbHtml::label(Yii::t("several","Curr"),"",array('class'=>"col-sm-2 control-label"));
        $html .='<div class="col-sm-5">';
        $html .=TbHtml::textField($className."[info_arr][$firm_id][curr]",$arr["curr"],array('readonly'=>($bool)));
        $html .=TbHtml::hiddenField($className."[info_arr][$firm_id][firm_id]",$firm_id);
        $html .='</div></div>';


        $html .= '<div class="form-group">';
        $html .=TbHtml::label(Yii::t("several","Info Arr"),"",array('class'=>"col-sm-2 control-label"));
        $html .='<div class="col-sm-8">';
        $html .= "<table class='table table-bordered'><thead><tr><th width='50%'>".Yii::t('several','Amt Name')."</th><th width='50%'>".Yii::t('several','Amt Num')."</th>";
        if(!$bool){
            $html.="<th></th>";
        }
        $html.="</tr></thead><tbody class='amt_body' data-firm='$firm_id'>";
        if(!empty($arr["list"])){
            foreach ($arr["list"] as $key=>$list){
                $bgColor = floatval($list["amt_num"])>0?"bg-danger":"";
                $html .= $this->tBodyTdHtml(array("firm_id" =>$firm_id,"key" =>$key,"amt_name" =>$list["amt_name"],"amt_gt" =>$list["amt_gt"],"amt_num" =>$list["amt_num"],"bgColor" =>$bgColor,"readonly" =>$bool));
            }
        }else{
            //$html .= $this->tBodyTdHtml(array("firm_id" =>$firm_id,"key" =>0,"amt_name" =>"","amt_gt" =>1,"amt_num" =>"","bgColor" =>"","readonly" =>$bool));
        }
        $html.="</tbody>";
        if(!$bool){
            $html.="<tfoot><tr><td colspan='2'></td>";
            $html.="<td>".TbHtml::button(Yii::t('misc','Add'),array("class"=>"btn btn-primary addAmtTr"))."</td>";
            $html.="</tr></tfoot>";
        }
        $html.="</table></div></div>";
        return $html;
    }


    //刪除验证
	public function validateDelete(){
/*        $rows = Yii::app()->db->createCommand()->select()->from("sev_customer")
            ->where('id=:id and amt=0', array(':id'=>$this->id))->queryRow();
        if ($rows){
            return false;
        }*/
        return false;
    }

	public function retrieveData($index)
	{
        $firm_str = Yii::app()->user->firm();
        $rows = Yii::app()->db->createCommand()->select("a.id as s_id,a.customer_id,a.firm_id,a.curr,a.amt,d.*,b.client_code,b.customer_name,c.company_code,countdoc('CUST',a.id) as custdoc")->from("sev_customer_firm a")
            ->leftJoin("sev_customer d","a.customer_id=d.id")
            ->leftJoin("sev_company b","d.company_id=b.id")
            ->leftJoin("sev_group c","d.group_id=c.id")
            ->where("a.id=:id and a.firm_id in ($firm_str)", array(':id'=>$index))->queryAll();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$this->id = $row['s_id'];
				$this->customer_id = $row['customer_id'];
				$this->firm_id = $row['firm_id'];
				$this->client_code = $row['client_code'];
                $this->customer_name = $row['customer_name'];
                $this->customer_year = $row['customer_year'];
                $this->company_code = $row['company_code'];

                $this->staff_id = $row['staff_id'];
                $this->salesman_id = $row['salesman_id'];
                $this->acca_username = $row['acca_username'];
                $this->acca_phone = $row['acca_phone'];
                $this->acca_remark = $row['acca_remark'];
                $this->acca_fun = $row['acca_fun'];
                $this->acca_lang = $row['acca_lang'];
                $this->acca_discount = $row['acca_discount'];
                $this->lud = $row['lud'];

                $this->no_of_attm['cust'] = $row['custdoc'];
/*                $this->curr = $row['curr'];
                $this->amt = $row['amt'];
                $this->info_arr = $info_arr;*/
                $this->refreshCustomerList();
				break;
			}
		}
		return true;
	}

	protected function refreshCustomerList(){
        $firm_str = Yii::app()->user->firm();
        $rows = Yii::app()->db->createCommand()->select("a.*,b.firm_name")->from("sev_customer_firm a")
            ->leftJoin("sev_firm b","b.id= a.firm_id")
            ->where("a.customer_id=:customer_id and a.firm_id in($firm_str)",array(':customer_id'=>$this->customer_id))->queryAll();
        if($rows){
            foreach ($rows as &$row){
                $list = Yii::app()->db->createCommand()->select("*")->from("sev_customer_info")
                    ->where("firm_cus_id=:firm_cus_id",array(':firm_cus_id'=>$row["id"]))->queryAll();
                if($list){
                    $row["list"] = $list;
                }else{
                    $row["list"] = array();
                }
            }
        }else{
            $rows = array();
        }
        $this->info_arr = $rows;
    }
	
	public function saveData(){
        $firm_str = Yii::app()->user->firm();
        $firm_list = Yii::app()->user->firm_list();
        $list = $this->info_arr;
        $lcu = Yii::app()->user->id;
        Yii::app()->db->createCommand()->update('sev_customer', array(
            'acca_username'=>$this->acca_username,
            'acca_phone'=>$this->acca_phone,
            'acca_remark'=>$this->acca_remark,
            'acca_fun'=>$this->acca_fun,
            'acca_lang'=>$this->acca_lang,
            'acca_discount'=>$this->acca_discount
        ), 'id=:id', array(':id'=>$this->customer_id));
        foreach ($list as $key => $value){
            if(!in_array($key,$firm_list)){
                return false;
            }
            $row = Yii::app()->db->createCommand()->select("*")->from("sev_customer_firm")
                ->where('customer_id=:customer_id and firm_id=:firm_id', array(':customer_id'=>$this->customer_id,':firm_id'=>$key))->queryRow();
            if($row){
                //var_dump($value);die();
                if(!key_exists("amt",$value)){
                    $value["amt"] = 0;
                }
                Yii::app()->db->createCommand()->update('sev_customer_firm', array(
                    'curr'=>$value["curr"],
                    'amt'=>$value["amt"],
                ), 'id=:id', array(':id'=>$row["id"]));

                Yii::app()->db->createCommand()->delete('sev_customer_info', 'firm_cus_id=:firm_cus_id', array(':firm_cus_id'=>$row["id"]));
                if(!empty($value["list"])){
                    foreach ($value["list"] as $item){
                        $item["firm_cus_id"]=$row["id"];
                        $item["customer_id"]=$row["customer_id"];
                        $item["lcu"]=$lcu;
                        $item["lcd"]=date("Y-m-d H:i:s");
                        Yii::app()->db->createCommand()->insert("sev_customer_info", $item);
                    }
                }
            }
        }

        //保存備註
        Yii::app()->db->createCommand()->insert("sev_remark_list", array(
            "firm_cus_id"=>$this->id,
            "remark"=>$this->remark,
            "lcu"=>$lcu,
        ));
	}


}
