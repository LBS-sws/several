<legend class="text-right"><span class="btn-search-tar glyphicon glyphicon-chevron-up"></span></legend>
<?php
$modelName = get_class($this->model);
?>
<div class="form-group">
    <?php
    foreach (array("id","client_code","customer_name","company_code") as $item){
        echo TbHtml::label($this->model->getAttributeLabel($item),$modelName."[$item]",array("class"=>"col-sm-1 control-label"));
        echo '<div class="col-sm-2">';
        echo TbHtml::textField($modelName."[$item]",$this->model[$item],array("class"=>"form-control"));
        echo '</div>';
    }
    ?>
</div>
<div class="form-group">
    <?php
    foreach (array("acca_username","acca_phone","acca_fax","acca_fun") as $item){
        echo TbHtml::label($this->model->getAttributeLabel($item),$modelName."[$item]",array("class"=>"col-sm-1 control-label"));
        echo '<div class="col-sm-2">';
        echo TbHtml::textField($modelName."[$item]",$this->model[$item],array("class"=>"form-control"));
        echo '</div>';
    }
    ?>
</div>
<div class="form-group">
    <?php
    foreach (array("listing_name","listing_email","listing_fax","new_month") as $item){
        echo TbHtml::label($this->model->getAttributeLabel($item),$modelName."[$item]",array("class"=>"col-sm-1 control-label"));
        echo '<div class="col-sm-2">';
        echo TbHtml::textField($modelName."[$item]",$this->model[$item],array("class"=>"form-control"));
        echo '</div>';
    }
    ?>
</div>
<div class="form-group">
    <?php
    foreach (array("refer_code","head_worker","other_worker","advance_name") as $item){
        echo TbHtml::label($this->model->getAttributeLabel($item),$modelName."[$item]",array("class"=>"col-sm-1 control-label"));
        echo '<div class="col-sm-2">';
        echo TbHtml::textField($modelName."[$item]",$this->model[$item],array("class"=>"form-control"));
        echo '</div>';
    }
    ?>
</div>

<div class="form-group">
    <?php
    $arr = array(
        "salesman_id"=>StaffForm::getStaffList(),
        "staff_id"=>StaffForm::getStaffList(),
        "group_type"=>array(""=>"","0"=>Yii::t("several","not group"),"1"=>Yii::t("several","is group")),
        "on_off"=>FunctionForm::getServiceList(true)
    );
    foreach ($arr as $item=>$list){
        echo TbHtml::label($this->model->getAttributeLabel($item),$modelName."[$item]",array("class"=>"col-sm-1 control-label"));
        echo '<div class="col-sm-2">';
        echo TbHtml::dropDownList($modelName."[$item]",$this->model[$item],$list,array("class"=>"form-control"));
        echo '</div>';
    }
    ?>
</div>