
<tr>
    <td><?php echo $this->record['id']; ?></td>
    <td><?php echo $this->record['handle_name']; ?></td>
    <td><?php echo $this->record['file_name']; ?></td>
    <td><?php echo $this->record['file_type']; ?></td>
    <td><?php echo $this->record['lcd']; ?></td>
    <td><?php echo $this->record['lud']; ?></td>
    <td>
        <?php
        if ($this->record['status']=='F'){
            $dlnk = Yii::app()->createUrl('import/view',array('index'=>$this->record['id']));
            echo TbHtml::Button('<span class="fa fa-download"></span> '.Yii::t('misc','Download'), array('submit'=>$dlnk,'size' => TbHtml::BUTTON_SIZE_SMALL));
        }else{
            echo $this->record['state'];
        }
        ?>
    </td>
</tr>
