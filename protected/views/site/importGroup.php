
<?php
 echo '<form action="" method="post" enctype="multipart/form-data" class="form-horizontal" name="'.$name.'">';
?>

<?php
	$ftrbtn = array();
    $ftrbtn[] = TbHtml::button(Yii::t('dialog','Upload'), array('id'=>"importUp",'submit'=>$submit));
    $ftrbtn[] = TbHtml::button(Yii::t('dialog','Close'), array('id'=>"btnWFClose",'data-dismiss'=>'modal','color'=>TbHtml::BUTTON_COLOR_PRIMARY));
	$this->beginWidget('bootstrap.widgets.TbModal', array(
					'id'=>'importGroup',
					'header'=>Yii::t('several','Import File'),
					'footer'=>$ftrbtn,
					'show'=>false,
				));
?>

<div class="form-group">
    <label class="col-sm-2 control-label"><?php echo Yii::t("several","file");?></label>
    <div class="col-sm-6">
        <?php echo TbHtml::fileField($name.'[file]',"",array("class"=>"form-control")); ?>
    </div>
</div>

<?php
	$this->endWidget(); 
?>
<?php
echo '</form>';
?>
