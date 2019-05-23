
<tr class='clickable-row <?php echo $this->record['color']; ?>' data-href='<?php echo $this->getLink('BC03', 'searchFirm/view', 'searchFirm/view', array('index'=>$this->record['id']));?>'>


    <td><?php echo $this->needHrefButton('BC03', 'searchFirm/view', 'view', array('index'=>$this->record['id'])); ?></td>

    <td><?php echo $this->record['firm_name']; ?></td>
    <td><?php echo $this->record['occurrences_num']; ?></td>
    <td><?php echo $this->record['collection_num']; ?></td>
    <td><?php echo $this->record['collection']; ?></td>
</tr>
