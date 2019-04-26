<?php
if (empty($model->id)&&$model->scenario == "edit"){
    $this->redirect(Yii::app()->createUrl('customer/index'));
}
$this->pageTitle=Yii::app()->name . ' - customer Form';
?>
<?php $form=$this->beginWidget('TbActiveForm', array(
'id'=>'customer-form',
'enableClientValidation'=>true,
'clientOptions'=>array('validateOnSubmit'=>true),
'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL,
    'htmlOptions'=>array('enctype' => 'multipart/form-data')
)); ?>

<section class="content-header">
	<h1>
		<strong><?php echo Yii::t('app','Customer From'); ?></strong>
	</h1>
<!--
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">Layout</a></li>
		<li class="active">Top Navigation</li>
	</ol>
-->
</section>

<section class="content">
	<div class="box"><div class="box-body">
	<div class="btn-group" role="group">
		<?php echo TbHtml::button('<span class="fa fa-reply"></span> '.Yii::t('misc','Back'), array(
				'submit'=>Yii::app()->createUrl('customer/index')));
		?>

        <?php if ($model->scenario!='view'): ?>
            <?php echo TbHtml::button('<span class="fa fa-upload"></span> '.Yii::t('misc','Save'), array(
                'submit'=>Yii::app()->createUrl('customer/save')));
            ?>
        <?php endif ?>
	</div>
            <div class="btn-group pull-right" role="group">
                <?php
                $counter = ($model->no_of_attm['cust'] > 0) ? ' <span id="doccust" class="label label-info">'.$model->no_of_attm['cust'].'</span>' : ' <span id="doccust"></span>';
                echo TbHtml::button('<span class="fa  fa-file-text-o"></span> '.Yii::t('misc','Attachment').$counter, array(
                        'name'=>'btnFile','id'=>'btnFile','data-toggle'=>'modal','data-target'=>'#fileuploadcust',)
                );
                ?>
            </div>
	</div></div>

	<div class="box box-info">
		<div class="box-body">
			<?php echo $form->hiddenField($model, 'scenario'); ?>
			<?php echo $form->hiddenField($model, 'id'); ?>
			<?php echo $form->hiddenField($model, 'customer_id'); ?>
			<?php echo $form->hiddenField($model, 'firm_id'); ?>
			<?php echo $form->hiddenField($model, 'salesman_id'); ?>
			<?php echo $form->hiddenField($model, 'staff_id'); ?>

            <legend><?php echo Yii::t("several","clients info"); ?></legend>
            <div class="form-group">
                <?php echo $form->labelEx($model,'client_code',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'client_code',
                        array('readonly'=>(true))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'customer_name',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'customer_name',
                        array('readonly'=>(true))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'company_code',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'company_code',
                        array('readonly'=>(true))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'salesman_id',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo TbHtml::textField("salesman_id",StaffForm::getStaffNameToId($model->salesman_id),array('readonly'=>(true))) ?>
                </div>
                <?php echo $form->labelEx($model,'phone',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo TbHtml::textField("phone",StaffForm::getStaffPhoneToId($model->staff_id),array('readonly'=>(true))) ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'staff_id',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo TbHtml::textField("staff_id",StaffForm::getStaffNameToId($model->staff_id),array('readonly'=>(true))) ?>
                </div>
                <?php echo $form->labelEx($model,'phone',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo TbHtml::textField("phone",StaffForm::getStaffPhoneToId($model->staff_id),array('readonly'=>(true))) ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'customer_year',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'customer_year',
                        array('readonly'=>(true))
                    ); ?>
                </div>
                <?php echo $form->labelEx($model,'lud',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'lud',
                        array('readonly'=>(true))
                    ); ?>
                </div>
            </div>
            <legend>&nbsp;</legend>
            <div class="form-group">
                <?php echo $form->labelEx($model,'acca_username',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'acca_username',
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
                <?php echo $form->labelEx($model,'acca_phone',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'acca_phone',
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'acca_lang',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->dropDownList($model, 'acca_lang',FunctionForm::getAllLang(),
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
                <?php echo $form->labelEx($model,'acca_discount',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'acca_discount',
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'acca_remark',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-5">
                    <?php echo $form->textArea($model, 'acca_remark',
                        array('readonly'=>($model->scenario=='view'),'rows'=>4)
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'acca_fun',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'acca_fun',
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
                <?php echo $form->labelEx($model,'payment',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'payment',
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'remark',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-5">
                    <?php echo $form->textArea($model, 'remark',
                        array('readonly'=>($model->scenario=='view'),'rows'=>4)
                    ); ?>
                </div>
            </div>
            <legend><?php echo Yii::t("several","arrears info"); ?></legend>
            <?php

            $tabs = array();
            if(!empty($model->info_arr)){
                foreach ($model->info_arr as $key=>$item){
                    $flag = $item["firm_id"]==$model->firm_id;
                    $tabs[] = array(
                        'label'=>$item["firm_name"],
                        'content'=>$model->printInfoBodyNew($item,$form),
                        'active'=>$flag,
                    );
                }
            }

            $this->widget('bootstrap.widgets.TbTabs', array(
                'tabs'=>$tabs,
            ));
            //echo $model->printInfoBody();

            ?>
		</div>
	</div>
</section>

<?php
$this->renderPartial('//site/fileupload',array('model'=>$model,
    'form'=>$form,
    'doctype'=>'CUST',
    'header'=>Yii::t('dialog','File Attachment'),
    'ronly'=>($model->scenario=='view'),
));
?>

<?php
Script::genFileUpload($model,$form->id,'CUST');
$addHtml = $model->tBodyTdHtml();
echo "<xmp id='xmpHidden' class='hidden'>".$addHtml."</xmp>";
$js = "
xmpHidden = $('#xmpHidden').text();
$('#xmpHidden').remove();
$('.addAmtTr').on('click',function(){
    var tbody = $(this).parents('tfoot:first').prev('tbody.amt_body');
    var key = tbody.children('tr:last').data('key');
    var firm_id = tbody.data('firm');
    console.log(tbody);
    if(key == undefined){
        key = 0;
    }
    key++;
    //console.log(xmpHidden);
    var html=xmpHidden.replace(/:key/g,key);
    html=html.replace(/:firm_id/g,firm_id);
    tbody.append(html);
});
$('tbody.amt_body').delegate('.delWage','click',function(){
    $(this).parents('tr:first').remove();
});
";
Yii::app()->clientScript->registerScript('calcFunction',$js,CClientScript::POS_READY);
$js = Script::genDeleteData(Yii::app()->createUrl('customer/delete'));
Yii::app()->clientScript->registerScript('deleteRecord',$js,CClientScript::POS_READY);

$js = Script::genReadonlyField();
Yii::app()->clientScript->registerScript('readonlyClass',$js,CClientScript::POS_READY);
?>

<?php $this->endWidget(); ?>

</div><!-- form -->

