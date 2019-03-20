
<tr class='clickable-row' data-href='<?php echo $this->getLink('XR03', 'staff/edit', 'staff/view', array('index'=>$this->record['id']));?>'>


    <td><?php echo $this->drawEditButton('XR03', 'staff/edit', 'staff/view', array('index'=>$this->record['id'])); ?></td>



    <td><?php echo $this->record['id']; ?></td>
    <td><?php echo $this->record['staff_name']; ?></td>
    <td><?php echo $this->record['staff_type']; ?></td>
    <td><?php echo $this->record['staff_phone']; ?></td>
</tr>
