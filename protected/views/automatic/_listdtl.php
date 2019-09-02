
<tr class='clickable-row' data-href='<?php echo $this->getLink('XR05', 'automatic/edit', 'automatic/view', array('index'=>$this->record['id']));?>'>


    <td><?php echo $this->drawEditButton('XR05', 'automatic/edit', 'automatic/view', array('index'=>$this->record['id'])); ?></td>



    <td><?php echo $this->record['id']; ?></td>
    <td><?php echo $this->record['staff_name']; ?></td>
    <td><?php echo $this->record['min_num']; ?></td>
    <td><?php echo $this->record['max_num']; ?></td>
</tr>
