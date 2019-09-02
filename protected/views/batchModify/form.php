<?php
$this->pageTitle=Yii::app()->name . ' - batchModify Form';
?>
<?php $form=$this->beginWidget('TbActiveForm', array(
'id'=>'batchModify-form',
'enableClientValidation'=>true,
'clientOptions'=>array('validateOnSubmit'=>true),
'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL,
    'htmlOptions'=>array('enctype' => 'multipart/form-data')
)); ?>

<section class="content-header">
	<h1>
		<strong><?php echo Yii::t('app','Batch Modifying'); ?></strong>
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
        <?php echo TbHtml::button('<span class="fa fa-upload"></span> '.Yii::t('misc','Save'), array(
            'submit'=>Yii::app()->createUrl('batchModify/save')));
        ?>
	</div>
	</div></div>

	<div class="box box-info">
		<div class="box-body">
			<?php echo $form->hiddenField($model, 'scenario'); ?>

            <div class="form-group">
                <div class="col-sm-10 col-sm-offset-2">
                    <p class="form-control-static text-warning">请谨慎操作，修改后，集团下面的客户会统一修改。</p>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'group_id',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->dropDownList($model, 'group_id',GroupForm::getGroupCodeList(),
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>


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
                <?php echo $form->labelEx($model,'acca_fax',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'acca_fax',
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
                <?php echo $form->labelEx($model,'usual_date',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <?php echo $form->textField($model,'usual_date',
                            array('class'=>'form-control pull-right','readonly'=>($model->scenario=='view'),'class'=>'usual_date'));
                        ?>
                    </div>
                </div>
                <?php echo $form->labelEx($model,'acca_discount',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'acca_discount',
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'refer_code',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'refer_code',
                        array('readonly'=>($model->scenario=='view'),'class'=>'refer_code')
                    ); ?>
                </div>
                <?php echo $form->labelEx($model,'on_off',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->dropDownList($model, 'on_off',FunctionForm::getServiceList(),
                        array('readonly'=>(true),'class'=>'on_off')
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'head_worker',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'head_worker',
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
                <?php echo $form->labelEx($model,'other_worker',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'other_worker',
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'listing_name',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'listing_name',
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
                <?php echo $form->labelEx($model,'listing_email',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'listing_email',
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'listing_fax',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'listing_name',
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
                <?php echo $form->labelEx($model,'new_month',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'other_worker',
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'advance_name',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'advance_name',
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
                <?php echo $form->labelEx($model,'pay_type',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->dropDownList($model, 'pay_type',FunctionForm::getPayList(),
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'payment',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'payment',
                        array('readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
                <?php echo $form->labelEx($model,'acca_fun',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'acca_fun',
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
		</div>
	</div>
</section>

<?php
$js = "
$('body').delegate('input.refer_code','keyup',function(){
    var refer_code=$(this).val();
    if(refer_code == 6 || refer_code==8){
        $('select.on_off').val(0);
    }else{
        $('select.on_off').val(1);
    }
});
$('.usual_date').datepicker({autoclose: true, format: 'yyyy-mm-dd',language: 'zh_cn',endDate:new Date()});
";
Yii::app()->clientScript->registerScript('deleteRecord',$js,CClientScript::POS_READY);

$js = Script::genReadonlyField();
Yii::app()->clientScript->registerScript('readonlyClass',$js,CClientScript::POS_READY);
?>

<?php $this->endWidget(); ?>

</div><!-- form -->

