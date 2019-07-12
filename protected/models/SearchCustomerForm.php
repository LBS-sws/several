<?php

/**
 * UserForm class.
 * UserForm is the data structure for keeping
 * user form data. It is used by the 'user' action of 'SiteController'.
 */
class SearchCustomerForm extends CFormModel
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
    public $acca_discount;
    public $salesman_id;
    public $staff_id;
    public $lud;
    public $payment;

    public $on_off;
    public $pay_type;

    public $firm_name;
    public $info_arr=array();
    protected $validateMonth;
    public $remark;
    public $remark_list;
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
            'salesman_id'=>Yii::t('several','salesman'),
            'staff_id'=>Yii::t('several','assign staff'),
            'phone'=>Yii::t('several','phone'),
            'lud'=>Yii::t('several','last time'),

            'firm_name'=>Yii::t('several','Clients firm'),
            'curr'=>Yii::t("several","Curr"),

            'on_off'=>Yii::t('several','on off'),
            'pay_type'=>Yii::t('several','pay type'),

            'amt'=>Yii::t('several','Amt'),
            'remark'=>Yii::t('several','Update Remark'),
            'info_arr'=>Yii::t('several','Info Arr'),
            'payment'=>Yii::t('several','payment'),
        );
    }

    public function onlyReadySearch(){
        return $this->scenario=='view';
    }

    public function retrieveData($index)
    {
        //$firm_str = Yii::app()->user->firm();
        $rows = Yii::app()->db->createCommand()->select("f.id as s_id,f.curr,f.firm_id,d.*,b.client_code,b.customer_name,c.company_code")->from("sev_customer_firm f")
            ->leftJoin("sev_customer d","f.customer_id=d.id")
            ->leftJoin("sev_company b","d.company_id=b.id")
            ->leftJoin("sev_group c","d.group_id=c.id")
            ->where("f.id=:id", array(':id'=>$index))->queryAll();

        if (count($rows) > 0)
        {
            foreach ($rows as $row)
            {
                $this->id = $row['s_id'];
                //$this->customer_id = $row['customer_id'];
                $this->firm_id = $row['firm_id'];
                $this->client_code = $row['client_code'];
                $this->curr = $row['curr'];
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
                $this->firm_name = FirmForm::getFirmNameToId($row["firm_id"]);
                $this->on_off = $row['on_off'];
                $this->pay_type = $row['pay_type'];
                //$this->refreshCustomerList();
                break;
            }
        }
        return true;
    }

    public function printInfoBody(){
        $html = "";
        if(!empty($this->id)){
            $html = '<p>&nbsp;</p><div class="form-group">';
            $html .=TbHtml::label(Yii::t("several","Clients firm"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-5">';
            $html .=TbHtml::textField("firm_name",$this->firm_name,array('readonly'=>(true)));
            $html .='</div></div>';
            $html .= '<div class="form-group">';
            $html .=TbHtml::label(Yii::t("several","Curr"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-5">';
            $html .=TbHtml::textField("curr",$this->curr,array('readonly'=>(true)));
            $html .='</div></div>';
            //追數詳情
            $html .= '<div class="form-group">';
            $html .=TbHtml::label(Yii::t("several","Info Arr"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-5">';
            $html .=$this->getAmtHtml($this->id);
            $html .='</div></div>';
            //流程詳情
            $html .= '<div class="form-group">';
            $html .=TbHtml::label(Yii::t("dialog","Flow Info"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-9">';
            $html .=$this->getFlowInfoHtml($this->id);
            $html .='</div></div>';
            //附件詳情
            $html .= '<div class="form-group">';
            $html .=TbHtml::label(Yii::t("dialog","File Attachment"),"",array('class'=>"col-sm-2 control-label"));
            $html .='<div class="col-sm-7">';
            $html .=$this->getFileHtml($this->id);
            $html .='</div></div>';

        }
        return $html;
    }

    //追數詳情
    public function getAmtHtml($firm_cus_id){
        $html = "";
        $info_all = Yii::app()->db->createCommand()->select("*")->from("sev_customer_info")
            ->where("firm_cus_id=:id", array(':id'=>$firm_cus_id))->order("CAST(amt_name as SIGNED) desc")->queryAll();
        if($info_all){
            $sum = 0;
            $html .= "<table class='table table-bordered'><thead><tr>";
            $html .= "<th width='50%'>".Yii::t('several','Amt Name')."</th>";
            $html .= "<th width='50%'>".Yii::t('several','Amt Num')."</th>";
            $html .= "</tr><tbody>";
            $monthList = UploadExcelForm::getMonth();
            foreach ($info_all as $info){
                $sum+=$info['amt_num'];
                $color = $info['amt_num']>0?"bg-danger":"";
                $html.="<tr class='$color'>";
                if($info["amt_gt"] != 1){
                    $html.="<td>".$monthList[$info['amt_name']]."或之前</td>";
                }else{
                    $html.="<td>".$monthList[$info['amt_name']]."</td>";
                }
                $html.="<td>".$info['amt_num']."</td>";
                $html.="</tr>";
            }
            $html .= "</tbody><tfoot><tr><td colspan='2' class='text-right'>总计：$sum</td></tr></tfoot></table>";
        }else{
            $html = '<p class="form-control-static text-warning">无</p>';
        }

        return $html;
    }

    //流程詳情
    public function getFlowInfoHtml($firm_cus_id){
        $html = "";
        $info_all = Yii::app()->db->createCommand()->select("a.*,b.disp_name")->from("sev_remark_list a")
            ->leftJoin("sec_user b","a.lcu=b.username")
            ->where("a.firm_cus_id=:id", array(':id'=>$firm_cus_id))->order("lcd desc")->queryAll();
        if($info_all){
            $html .= "<table class='table table-bordered'><thead><tr>";
            $html .= "<th width='20%'>".Yii::t('dialog','Date')."</th>";
            $html .= "<th width='70%'>".Yii::t('several','Remark')."</th>";
            $html .= "<th width='10%'>".Yii::t('dialog','Resp. User')."</th>";
            $html .= "</tr><tbody>";
            foreach ($info_all as $info){
                $html.="<tr>";
                $html.="<td>".$info['lcd']."</td>";
                $html.="<td>".$info['remark']."</td>";
                $html.="<td>".$info['disp_name']."</td>";
                $html.="</tr>";
            }
            $html .= "</tbody></table>";
        }else{
            $html = '<p class="form-control-static text-warning">无</p>';
        }

        return $html;
    }

    //附件詳情
    private function getFileHtml($firm_cus_id)
    {
        $html = "";
        $info_all = Yii::app()->db->createCommand()->select("*")->from("sev_customer_info")
            ->where("firm_cus_id=:id", array(':id' => $firm_cus_id))->order("CAST(amt_name as SIGNED) desc")->queryAll();
        if ($info_all) {
            $html = "";
            $info_all = Yii::app()->db->createCommand()->select("a.id, a.doc_type_code, a.doc_id,
            b.id as file_id, b.display_name, b.archive, b.lcd, b.file_type ")->from("dm_master a")
                ->leftJoin("dm_file b", "a.id=b.mast_id")
                ->where("a.doc_type_code='CUST' and a.doc_id=:id and b.remove='N'", array(':id' => $firm_cus_id))
                ->order("b.display_name, b.lcd desc")->queryAll();
            if ($info_all) {
                $html .= "<table class='table table-bordered'><thead><tr>";
                $html .= "<th></th>";
                $html .= "<th width='70%'>" . Yii::t('dialog','File Name'). "</th>";
                $html .= "<th width='30%'>" . Yii::t('dialog', 'Date') . "</th>";
                $html .= "</tr><tbody>";
                foreach ($info_all as $info) {
                    $href = Yii::app()->createUrl('searchCompany/fileDownload',array("mastId"=>$info["id"],"docId"=>$info["doc_id"],"fileId"=>$info["file_id"],"doctype"=>"CUST",));
                    $html .= "<tr>";
                    $html .= "<td><a href='$href' target='_blank'><span class='fa fa-download'></span></a></td>";
                    $html .= "<td>" . $info['display_name'] . "</td>";
                    $html .= "<td>" . $info['lcd'] . "</td>";
                    $html .= "</tr>";
                }
                $html .= "</tbody></table>";
            } else {
                $html = '<p class="form-control-static text-warning">无</p>';
            }

            return $html;
        }
    }
}
