
<tr class='clickable-row' data-href='<?php echo $this->getLink('XR04', 'firm/edit', 'firm/view', array('index'=>$this->record['id']));?>'>


    <td><?php echo $this->drawEditButton('XR04', 'firm/edit', 'firm/view', array('index'=>$this->record['id'])); ?></td>



    <td><?php echo $this->record['id']; ?></td>
    <td><?php echo $this->record['firm_name']; ?></td>
    <td><?php echo $this->record['z_index']; ?></td>
</tr>
