<?php
$this->pageTitle=Yii::app()->name . ' - SearchCustomer';
?>

<?php $form=$this->beginWidget('TbActiveForm', array(
    'id'=>'searchCustomer-list',
    'enableClientValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>true,),
    'layout'=>TbHtml::FORM_LAYOUT_INLINE,
)); ?>

<section class="content-header">
    <h1>
        <strong><?php echo Yii::t('app','Search Customer'); ?></strong>
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
    <?php
    $search = array(
        'firm_name',
        'client_code',
        'customer_name',
        'customer_year',
        'company_code',
        'curr',
    );
    $search_add_html="";
    $modelName = get_class($model);
    $search_add_html .= TbHtml::dropDownList($modelName.'[searchArrears]',$model->searchArrears,$model->getArrearsList(),
        array('size'=>15,'placeholder'=>Yii::t('misc','Start Date'),"class"=>"form-control","id"=>"start_time"));

   $this->widget('ext.layout.ListPageWidget', array(
        'title'=>Yii::t('app','Customer List'),
        'model'=>$model,
        'viewhdr'=>'//searchCustomer/_listhdr',
        'viewdtl'=>'//searchCustomer/_listdtl',
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

