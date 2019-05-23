<?php
if (empty($model->id)&&$model->scenario == "edit"){
    $this->redirect(Yii::app()->createUrl('clients/index'));
}
$this->pageTitle=Yii::app()->name . ' - clients Form';
?>
<?php $form=$this->beginWidget('TbActiveForm', array(
'id'=>'clients-form',
'enableClientValidation'=>true,
'clientOptions'=>array('validateOnSubmit'=>true),
'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL,
    'htmlOptions'=>array('enctype' => 'multipart/form-data')
)); ?>

<section class="content-header">
	<h1>
		<strong><?php echo Yii::t('several','After Clients Form'); ?></strong>
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
				'submit'=>Yii::app()->createUrl('clients/index')));
		?>

        <?php if ($model->scenario!='view'): ?>
            <?php echo TbHtml::button('<span class="fa fa-upload"></span> '.Yii::t('misc','Save'), array(
                'submit'=>Yii::app()->createUrl('clients/save')));
            ?>
            <?php if ($model->scenario=='edit'): ?>
                <?php
                echo TbHtml::button('<span class="fa fa-file-o"></span> '.Yii::t('misc','Add'), array(
                    'submit'=>Yii::app()->createUrl('clients/new'),
                ));
                ?>
                <?php echo TbHtml::button('<span class="fa fa-remove"></span> '.Yii::t('misc','Delete'), array(
                        'name'=>'btnDelete','id'=>'btnDelete','data-toggle'=>'modal','data-target'=>'#removedialog',)
                );
                ?>
            <?php endif; ?>
        <?php endif ?>
	</div>
	</div></div>

	<div class="box box-info">
		<div class="box-body">
			<?php echo $form->hiddenField($model, 'scenario'); ?>
			<?php echo $form->hiddenField($model, 'id'); ?>

            <div class="form-group">
                <?php echo $form->labelEx($model,'client_code',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-8">
                    <?php echo $form->dropDownList($model, 'company_id',CompanyForm::getCompanyList(),
                        array('readonly'=>($model->scenario!='new'),"id"=>"company_id")
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'customer_name',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-5">
                    <?php echo $form->textField($model, 'customer_name',
                        array('readonly'=>(true),"id"=>"client_code")
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'group_id',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-5">
                    <?php echo $form->dropDownList($model, 'group_id',GroupForm::getGroupCodeList(),
                        array('readonly'=>($model->scenario=='view'),"id"=>"group_id")
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'staff_id',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->dropDownList($model, 'staff_id',StaffForm::getStaffList(),
                        array('readonly'=>($model->scenario=='view'),"id"=>"staff_id")
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'salesman_id',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->dropDownList($model, 'salesman_id',StaffForm::getStaffList(),
                        array('readonly'=>($model->scenario=='view'),"id"=>"salesman_id")
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'firm_name_id',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-10">
                    <?php echo $form->inlineCheckBoxList($model, 'firm_name_id',FirmForm::getFirmList(),
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-5 col-sm-offset-2">
                    <p class="form-control-static text-warning">可追数公司的意思是：該客戶在LBS哪些公司有欠款</p>
                </div>
            </div>
		</div>
	</div>
</section>

<?php
$this->renderPartial('//site/removedialog');
?>
<?php
$js = "
    $('#company_id').on('change',function(){
        $.ajax({
            type: 'post',
            url: '".Yii::app()->createUrl("clients/ajaxCompanyList")."',
            dataType: 'json',
            data: {
                'company_id':$('#company_id').val()
            },
            success: function(json) {
                if(json['status'] == 1){
                    var data = json['data'];
                    $('#client_code').val(data['customer_name']);
                    $('#group_id').val(data['group_id']);
                    $('#staff_id').val(data['assign_id']);
                }
            }
        });
    });
";
Yii::app()->clientScript->registerScript('calcFunction',$js,CClientScript::POS_READY);
$js = Script::genDeleteData(Yii::app()->createUrl('clients/delete'));
Yii::app()->clientScript->registerScript('deleteRecord',$js,CClientScript::POS_READY);

$js = Script::genReadonlyField();
Yii::app()->clientScript->registerScript('readonlyClass',$js,CClientScript::POS_READY);
?>

<?php $this->endWidget(); ?>

</div><!-- form -->

