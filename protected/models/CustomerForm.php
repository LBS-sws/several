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
	public $acca_fax;
	public $acca_discount;
	public $salesman_id;
	public $staff_id;
	public $lud;
	public $payment;

	public $on_off;
	public $pay_type;

    public $refer_code;
    public $usual_date;
    public $head_worker;
    public $other_worker;
    public $advance_name;
    public $listing_name;
    public $listing_email;
    public $listing_fax;
    public $new_month;
    public $lbs_month;
    public $other_month;

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
            'company_code'=>Yii::t('several','Company Code'),

            'acca_username'=>Yii::t('several','accountant username'),
            'acca_phone'=>Yii::t('several','accountant phone'),
            'acca_lang'=>Yii::t('several','accountant lang'),
            'acca_discount'=>Yii::t('several','discount'),
            'acca_remark'=>Yii::t('several','accountant remark'),
            'acca_fun'=>Yii::t('several','method'),
            'acca_fax'=>Yii::t('several','accountant fax'),
            'salesman_id'=>Yii::t('several','salesman'),
            'staff_id'=>Yii::t('several','assign staff'),
            'phone'=>Yii::t('several','phone'),
            'lud'=>Yii::t('several','last time'),

            'on_off'=>Yii::t('several','on off'),
            'pay_type'=>Yii::t('several','pay type'),

            'curr'=>Yii::t('several','Curr'),
            'amt'=>Yii::t('several','Amt'),
            'remark'=>Yii::t('several','Update Remark'),
            'info_arr'=>Yii::t('several','Info Arr'),
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
        );
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			//array('id, position, leave_reason, remarks, email, staff_type, leader','safe'),
            array('id, customer_id, remark, firm_id, client_code, customer_name, company_code, curr, info_arr, pay_type, on_off
            ,acca_fax,refer_code,usual_date,head_worker,other_worker,advance_name,listing_name,listing_email,listing_fax,new_month,lbs_month,other_month,
            ,acca_username,acca_phone,acca_lang,acca_discount,acca_remark,acca_fun,salesman_id,staff_id,payment,lud','safe'),
			array('remark,id','required'),
			//array('info_arr','validateInfoArr'),  //因為無法修改欠款所以取消
			array('id','validateId'),
			array('refer_code','validateReferCode'),
            array('files, removeFileId, docMasterId, no_of_attm','safe'),
		);
	}

	public function validateReferCode($attribute, $params){
	    if($this->refer_code==6||$this->refer_code == 8){
	        $this->on_off = 0;
        }else{
	        $this->on_off = 1;
        }
    }

	public function validateId($attribute, $params){
        if (!empty($this->id)){
            $firm_str = Yii::app()->user->firm();
            $row = Yii::app()->db->createCommand()->select("a.customer_id")->from("sev_customer_firm a")
                ->leftJoin("sev_customer b","b.id=a.customer_id")
                ->where("b.id=:id and a.firm_id in ($firm_str)", array(':id'=>$this->id))->queryRow();
            if(!$row){
                $message = Yii::t('several','ID'). "不存在";
                $this->addError($attribute,$message);
                return false;
            }else{
                $this->customer_id = $this->id;
            }
        }
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

    public function ajaxUpdateHtml($id){
        $firm_str = Yii::app()->user->firm();
        $row = Yii::app()->db->createCommand()->select("c.client_code,c.customer_name,b.*")->from("sev_customer b")
            ->leftJoin("sev_company c","c.id = b.company_id")
            ->where("b.id=:id", array(':id'=>$id))->queryRow();
        if($row){
            $html = '<div class="modal-header"><button class="close" data-dismiss="modal" type="button">×</button><h4 class="modal-title">修改</h4></div>';

            $html.='<div class="modal-body">';
            $html .=TbHtml::hiddenField("updateWindow[id]",$row["id"]);

            $html .= '<div class="form-group">';
            $html .=TbHtml::label(Yii::t("several","Customer Code"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-4">';
            $html .=TbHtml::textField("updateWindow[client_code]",$row["client_code"],array('readonly'=>(true)));
            $html .='</div>';
            $html .=TbHtml::label(Yii::t("several","ID"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-4">';
            $html .=TbHtml::textField("updateWindow[id]",$row["id"],array('readonly'=>(true)));
            $html .='</div>';
            $html.='</div>';
            $html .= '<div class="form-group">';
            $html .=TbHtml::label(Yii::t("several","Customer Name"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-10">';
            $html .=TbHtml::textField("updateWindow[customer_name]",$row["customer_name"],array('readonly'=>(true)));
            $html .='</div></div>';


            $html .= '<div class="form-group">';
            $html .=TbHtml::label(Yii::t("several","accountant username"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-4">';
            $html .=TbHtml::textField("updateWindow[acca_username]",$row["acca_username"]);
            $html .='</div>';
            $html .=TbHtml::label(Yii::t("several","accountant phone"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-4">';
            $html .=TbHtml::textField("updateWindow[acca_phone]",$row["acca_phone"]);
            $html .='</div>';
            $html.='</div>';

            $html .= '<div class="form-group">';
            $html .=TbHtml::label(Yii::t("several","accountant lang"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-4">';
            $html .=TbHtml::dropDownList("updateWindow[acca_lang]",$row["acca_lang"],FunctionForm::getAllLang());
            $html .='</div>';
            $html .=TbHtml::label(Yii::t("several","accountant fax"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-4">';
            $html .=TbHtml::textField("updateWindow[acca_fax]",$row["acca_fax"]);
            $html .='</div>';
            $html.='</div>';

            $html .= '<div class="form-group">';
            $html .=TbHtml::label(Yii::t("several","usual date"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-4"><div class="input-group"><div class="input-group-addon"><i class="fa fa-calendar"></i></div>';
            $html .=TbHtml::textField("updateWindow[usual_date]",$row["usual_date"],array('class'=>"usual_date"));
            $html .='</div></div>';
            $html .=TbHtml::label(Yii::t("several","discount"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-4">';
            $html .=TbHtml::textField("updateWindow[acca_discount]",$row["acca_discount"]);
            $html .='</div>';
            $html.='</div>';

            $html .= '<div class="form-group">';
            $html .=TbHtml::label(Yii::t("several","refer code"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-4">';
            $html .=TbHtml::textField("updateWindow[refer_code]",$row["refer_code"],array("class"=>"refer_code"));
            $html .='</div>';
            $html .=TbHtml::label(Yii::t("several","on off"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-4">';
            $html .=TbHtml::dropDownList("updateWindow[on_off]",$row["on_off"],FunctionForm::getServiceList(),array("readonly"=>true,"class"=>"on_off"));
            $html .='</div>';
            $html.='</div>';

            $html .= '<div class="form-group">';
            $html .=TbHtml::label(Yii::t("several","head worker"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-4">';
            $html .=TbHtml::textField("updateWindow[head_worker]",$row["head_worker"]);
            $html .='</div>';
            $html .=TbHtml::label(Yii::t("several","other worker"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-4">';
            $html .=TbHtml::textField("updateWindow[other_worker]",$row["other_worker"]);
            $html .='</div>';
            $html.='</div>';

            $html .= '<div class="form-group">';
            $html .=TbHtml::label(Yii::t("several","listing name"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-4">';
            $html .=TbHtml::textField("updateWindow[listing_name]",$row["listing_name"]);
            $html .='</div>';
            $html .=TbHtml::label(Yii::t("several","listing email"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-4">';
            $html .=TbHtml::textField("updateWindow[listing_email]",$row["listing_email"]);
            $html .='</div>';
            $html.='</div>';

            $html .= '<div class="form-group">';
            $html .=TbHtml::label(Yii::t("several","listing fax"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-4">';
            $html .=TbHtml::textField("updateWindow[listing_fax]",$row["listing_fax"]);
            $html .='</div>';
            $html .=TbHtml::label(Yii::t("several","new month"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-4">';
            $html .=TbHtml::textField("updateWindow[new_month]",$row["new_month"]);
            $html .='</div>';
            $html.='</div>';

            $html .= '<div class="form-group">';
            $html .=TbHtml::label(Yii::t("several","advance name"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-4">';
            $html .=TbHtml::textField("updateWindow[advance_name]",$row["advance_name"]);
            $html .='</div>';
            $html .=TbHtml::label(Yii::t("several","pay type"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-4">';
            $html .=TbHtml::dropDownList("updateWindow[pay_type]",$row["pay_type"],FunctionForm::getPayList());
            $html .='</div>';
            $html.='</div>';

            $html .= '<div class="form-group">';
            $html .=TbHtml::label(Yii::t("several","payment"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-4">';
            $html .=TbHtml::textField("updateWindow[payment]",$row["payment"]);
            $html .='</div>';
            $html .=TbHtml::label(Yii::t("several","method"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-4">';
            $html .=TbHtml::textField("updateWindow[acca_fun]",$row["acca_fun"]);
            $html .='</div>';
            $html.='</div>';

            $html .= '<div class="form-group">';
            $html .=TbHtml::label(Yii::t("several","Update Remark"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-9">';
            $html .=TbHtml::textArea("updateWindow[remark]","",array("rows"=>4));
            $html .='</div></div>';

            $html.='</div>';

            $html.='<div class="modal-footer">';
            $html.='<a class="btn btn-default pull-left" type="button" href="'.Yii::app()->createUrl('customer/edit',array("index"=>$id)).'">详情</a>';
            $html.='<button class="btn btn-default pull-left" id="btn-ago" type="button">上次内容</button>';
            $html.='<button class="btn btn-primary" type="submit">提交</button>';
            $html.='</div>';
            return array("status"=>1,"html"=>$html);
        }else{
            return array("status"=>0);
        }
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

    public function printInfoBodyNew($arr,$form){
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
        $html .=TbHtml::hiddenField($className."[info_arr][$firm_id][id]",$arr["id"]);
        $html .='</div></div>';
        if(!empty($firm_id)) {
            //追數詳情
            $html .= '<div class="form-group">';
            $html .= TbHtml::label(Yii::t("several", "Info Arr"), "", array('class' => "col-sm-2 control-label"));
            $html .= '<div class="col-sm-5">';
            $html .= SearchCustomerForm::getAmtHtml($arr["id"]);
            $html .= '</div></div>';
        }
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


    public function getFirmList(){
        $firm_str = Yii::app()->user->firm();
        $arr=array();
        $rows = Yii::app()->db->createCommand()->select("id,firm_name")->from("sev_firm")->where("id in ($firm_str)")->order("z_index desc")->queryAll();
        if($rows){
            foreach ($rows as $row){
                $arr[$row["id"]] = $row["firm_name"];
            }
        }
        return $arr;
    }

	public function retrieveData($index)
	{
        $firm_str = Yii::app()->user->firm();
        $rows = Yii::app()->db->createCommand()->select("d.*,b.client_code,b.customer_name,c.company_code,countdoc('CUST',d.id) as custdoc")
            ->from("sev_customer d")
            ->leftJoin("sev_company b","d.company_id=b.id")
            ->leftJoin("sev_group c","d.group_id=c.id")
            ->where("d.id=:id", array(':id'=>$index))->queryAll();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$this->id = $row['id'];
				$this->customer_id = $row['id'];

				$this->client_code = $row['client_code'];
                $this->customer_name = $row['customer_name'];
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
                $this->payment = $row['payment'];
                $this->on_off = $row['on_off'];
                $this->pay_type = $row['pay_type'];
//acca_fax,refer_code,usual_date,head_worker,other_worker,advance_name,listing_name,listing_email,listing_fax,new_month,lbs_month,other_month,
                $this->acca_fax = $row['acca_fax'];
                $this->refer_code = $row['refer_code'];
                $this->usual_date = $row['usual_date'];
                $this->head_worker = $row['head_worker'];
                $this->other_worker = $row['other_worker'];
                $this->advance_name = $row['advance_name'];
                $this->listing_name = $row['listing_name'];
                $this->listing_email = $row['listing_email'];
                $this->listing_fax = $row['listing_fax'];
                $this->new_month = $row['new_month'];
                $this->lbs_month = $row['lbs_month'];
                $this->other_month = $row['other_month'];

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
/*        if($rows){
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
        }*/
        $this->info_arr = $rows;
    }
	
	public function saveData(){
        $firm_str = Yii::app()->user->firm();
        $firm_list = Yii::app()->user->firm_list();
        $list = $this->info_arr;
        $lcu = Yii::app()->user->id;
        $arr = array(
            'acca_username'=>$this->acca_username,
            'acca_phone'=>$this->acca_phone,
            'acca_remark'=>$this->acca_remark,
            'acca_fun'=>$this->acca_fun,
            'acca_lang'=>$this->acca_lang,
            'acca_fax'=>$this->acca_fax,
            'acca_discount'=>$this->acca_discount,

            'refer_code'=>$this->refer_code,
            'usual_date'=>$this->usual_date,
            'head_worker'=>$this->head_worker,
            'other_worker'=>$this->other_worker,
            'advance_name'=>$this->advance_name,
            'listing_name'=>$this->listing_name,
            'listing_email'=>$this->listing_email,
            'listing_fax'=>$this->listing_fax,
            'new_month'=>$this->new_month,

            'on_off'=>$this->on_off,
            'pay_type'=>$this->pay_type,
            'lud'=>date("Y-m-d H:i:s"),
            'status_type'=>'y',
            'payment'=>$this->payment
        );
        if(empty($this->refer_code)&&$this->refer_code !== 0){
            unset($arr["refer_code"]);
        }
//acca_fax,refer_code,usual_date,head_worker,other_worker,advance_name,listing_name,listing_email,listing_fax,new_month,lbs_month,other_month,
        Yii::app()->db->createCommand()->update('sev_customer', $arr, 'id=:id', array(':id'=>$this->customer_id));
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
                    //'amt'=>$value["amt"],
                ), 'id=:id', array(':id'=>$row["id"]));
/*
                Yii::app()->db->createCommand()->delete('sev_customer_info', 'firm_cus_id=:firm_cus_id', array(':firm_cus_id'=>$row["id"]));
                if(!empty($value["list"])){
                    foreach ($value["list"] as $item){
                        $item["firm_cus_id"]=$row["id"];
                        $item["customer_id"]=$row["customer_id"];
                        $item["lcu"]=$lcu;
                        $item["lcd"]=date("Y-m-d H:i:s");
                        Yii::app()->db->createCommand()->insert("sev_customer_info", $item);
                    }
                }*/
            }
        }

        //保存備註
        Yii::app()->db->createCommand()->insert("sev_remark_list", array(
            "customer_id"=>$this->id,
            "remark"=>$this->remark,
            "lcu"=>$lcu,
        ));
	}

	public function ajaxSaveData(){
        $lcu = Yii::app()->user->id;
        $arr = array(
            'acca_username'=>$this->acca_username,
            'acca_phone'=>$this->acca_phone,
            'acca_remark'=>$this->acca_remark,
            'acca_fun'=>$this->acca_fun,
            'acca_lang'=>$this->acca_lang,
            'acca_discount'=>$this->acca_discount,
            'acca_fax'=>$this->acca_fax,
            'payment'=>$this->payment,
            'on_off'=>$this->on_off,
            'pay_type'=>$this->pay_type,

            'refer_code'=>$this->refer_code,
            'usual_date'=>$this->usual_date,
            'head_worker'=>$this->head_worker,
            'other_worker'=>$this->other_worker,
            'advance_name'=>$this->advance_name,
            'listing_name'=>$this->listing_name,
            'listing_email'=>$this->listing_email,
            'listing_fax'=>$this->listing_fax,
            'new_month'=>$this->new_month,

            'status_type'=>'y',
            'lud'=>date("Y-m-d H:i:s")
        );
        if(empty($this->refer_code)&&$this->refer_code !== 0){
            unset($arr["refer_code"]);
        }
        Yii::app()->db->createCommand()->update('sev_customer', $arr, 'id=:id', array(':id'=>$this->customer_id));

        if (!empty($this->curr)){
            Yii::app()->db->createCommand()->update('sev_customer_firm', array(
                'curr'=>$this->curr
            ), 'id=:id', array(':id'=>$this->id));
        }
        //保存備註
        Yii::app()->db->createCommand()->insert("sev_remark_list", array(
            "customer_id"=>$this->id,
            "remark"=>$this->remark,
            "lcu"=>$lcu,
        ));
        $html = '<div class="modal-header"><button class="close" data-dismiss="modal" type="button">×</button><h4 class="modal-title">'.Yii::t('dialog','Information').'</h4></div>';

        $html.='<div class="modal-body">'.Yii::t('dialog','Save Done').'</div>';
        $html.='<div class="modal-footer"><button data-dismiss="modal" class="btn btn-primary" type="button">确定</button></div>';
        return array("status"=>1,"html"=>$html,"remarkHtml"=>CustomerList::getRemarkHtml($this->id));
	}

	public function getAjaxError(){
        $message = CHtml::errorSummary($this);

        $html = '<div class="modal-header"><button class="close" data-dismiss="modal" type="button">×</button><h4 class="modal-title">'.Yii::t('dialog','Validation Message').'</h4></div>';

        $html.='<div class="modal-body">'.$message.'</div>';
        $html.='<div class="modal-footer"><button data-dismiss="modal" class="btn btn-primary" type="button">确定</button></div>';
        return array("status"=>0,"html"=>$html);
    }

}
