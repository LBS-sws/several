
<tr class='clickable-row <?php echo $this->record['color']; ?>' data-href='<?php echo $this->getLink('BC04', 'searchStaff/view', 'searchFirm/view', array('index'=>$this->record['id']));?>'>


    <td><?php echo $this->needHrefButton('BC04', 'searchStaff/view', 'view', array('index'=>$this->record['id'])); ?></td>

    <td><?php echo $this->record['staff_name']; ?></td>
    <td><?php echo $this->record['staff_phone']; ?></td>
    <td><?php echo $this->record['occurrences_num']; ?></td>
    <td><?php echo $this->record['collection_num']; ?></td>
    <td><?php echo $this->record['collection']; ?></td>
</tr>
