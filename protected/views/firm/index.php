<?php
$this->pageTitle=Yii::app()->name . ' - Firm';
?>

<?php $form=$this->beginWidget('TbActiveForm', array(
    'id'=>'firm-list',
    'enableClientValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>true,),
    'layout'=>TbHtml::FORM_LAYOUT_INLINE,
)); ?>

<section class="content-header">
    <h1>
        <strong><?php echo Yii::t('app','Firm List'); ?></strong>
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
            <div style="padding: 5px;" class="text-danger">本頁面的數據方便系統識別LBS有幾個公司，只需要把6個公司錄入即可</div>
            <div class="btn-group" role="group">
                <?php
                //var_dump(Yii::app()->session['rw_func']);
                if (Yii::app()->user->validRWFunction('XR04'))
                    echo TbHtml::button('<span class="fa fa-file-o"></span> '.Yii::t('misc','Add'), array(
                        'submit'=>Yii::app()->createUrl('firm/new'),
                    ));
                ?>
            </div>
        </div>
    </div>
    <?php
    $search = array(
        'firm_name',
    );
/*    $search_add_html="";
    $modelName = get_class($model);
    $search_add_html .= TbHtml::dropDownList($modelName.'[searchArrears]',$model->searchArrears,$model->getArrearsList(),
        array('size'=>15,'placeholder'=>Yii::t('misc','Start Date'),"class"=>"form-control","id"=>"start_time"));*/

   $this->widget('ext.layout.ListPageWidget', array(
        'title'=>Yii::t('app','Firm List'),
        'model'=>$model,
        'viewhdr'=>'//firm/_listhdr',
        'viewdtl'=>'//firm/_listdtl',
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
?>

