<?php
$this->pageTitle=Yii::app()->name . ' - Group';
?>

<?php $form=$this->beginWidget('TbActiveForm', array(
    'id'=>'group-list',
    'enableClientValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>true,),
    'layout'=>TbHtml::FORM_LAYOUT_INLINE,
)); ?>

<section class="content-header">
    <h1>
        <strong><?php echo Yii::t('app','Group number'); ?></strong>
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
            <div style="padding: 5px;" class="text-danger">本頁面的數據對應Aging.xls文件中的data表格的數據</div>
            <div class="btn-group" role="group">
                <?php
                //var_dump(Yii::app()->session['rw_func']);
                if (Yii::app()->user->validRWFunction('XR01'))
                    echo TbHtml::button('<span class="fa fa-file-o"></span> '.Yii::t('misc','Add'), array(
                        'submit'=>Yii::app()->createUrl('group/new'),
                    ));
                ?>
            </div>
            <div class="btn-group pull-right" role="group">
                <?php
                if (Yii::app()->user->validRWFunction('XR01'))
                    echo TbHtml::button('<span class="fa fa-file-text-o"></span> '.Yii::t('several','Import File'), array(
                        'data-toggle'=>'modal','data-target'=>'#importGroup'));
                ?>
            </div>
        </div>
    </div>
    <?php
    $search = array(
        'company_code',
        'staff_name',
    );
/*    $search_add_html="";
    $modelName = get_class($model);
    $search_add_html .= TbHtml::dropDownList($modelName.'[searchArrears]',$model->searchArrears,$model->getArrearsList(),
        array('size'=>15,'placeholder'=>Yii::t('misc','Start Date'),"class"=>"form-control","id"=>"start_time"));*/

   $this->widget('ext.layout.ListPageWidget', array(
        'title'=>Yii::t('app','Group number'),
        'model'=>$model,
        'viewhdr'=>'//group/_listhdr',
        'viewdtl'=>'//group/_listdtl',
        'gridsize'=>'24',
        'height'=>'600',
       //'search_add_html'=>$search_add_html,
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
if (Yii::app()->user->validRWFunction('XR01'))
    $this->renderPartial('//site/importGroup',array('name'=>"UploadExcelForm","model"=>$model,"submit"=>Yii::app()->createUrl('group/import')));
?>

