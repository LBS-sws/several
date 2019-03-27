<?php
$this->pageTitle=Yii::app()->name . ' - searchStaff';
?>

<?php $form=$this->beginWidget('TbActiveForm', array(
    'id'=>'searchStaff-list',
    'enableClientValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>true,),
    'layout'=>TbHtml::FORM_LAYOUT_INLINE,
)); ?>

<section class="content-header">
    <h1>
        <strong><?php echo Yii::t('app','Search Staff'); ?></strong>
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
    <div class="box">
    <div class="box-body">
        <div style="display: inline-block" class="text-danger">
            <p>出现次数：LBS公司出现的次数</p>
            <p>待收款次数：LBS公司待收款的次数</p>
        </div>
        <!--
            <div class="btn-group pull-right" role="group">
                <?php
        echo TbHtml::button('<span class="fa fa-download"></span> '.Yii::t('several','export'), array(
            'submit'=>Yii::app()->createUrl('searchStaff/export'),
        ));
        ?>
            </div>
            -->
    </div>
    </div>
    <?php
    $search = array(
        'staff_name',
        'staff_phone'
    );
    $search_add_html="";
    $modelName = get_class($model);
    $search_add_html .= TbHtml::dropDownList($modelName.'[searchYear]',$model->searchYear,UploadExcelForm::getYear(),
        array('size'=>15,'placeholder'=>Yii::t('misc','Start Date'),"class"=>"form-control","id"=>"start_time"));

   $this->widget('ext.layout.ListPageWidget', array(
        'title'=>Yii::t('several','Staff List'),
        'model'=>$model,
        'viewhdr'=>'//searchStaff/_listhdr',
        'viewdtl'=>'//searchStaff/_listdtl',
        'gridsize'=>'24',
        'height'=>'600',
        'search_add_html'=>$search_add_html,
        'search'=>$search,
    ));
    ?>
</section>
<?php
echo $form->hiddenField($model,'pageNum');
echo $form->hiddenField($model,'totalRow');
echo $form->hiddenField($model,'orderField');
echo $form->hiddenField($model,'orderType');
?>
<?php $this->endWidget(); ?>

<?php
$js = Script::genTableRowClick();
Yii::app()->clientScript->registerScript('rowClick',$js,CClientScript::POS_READY);
?>

