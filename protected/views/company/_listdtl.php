
<tr class='clickable-row' data-href='<?php echo $this->getLink('XR02', 'company/edit', 'company/view', array('index'=>$this->record['id']));?>'>


    <td><?php echo $this->drawEditButton('XR02', 'company/edit', 'company/view', array('index'=>$this->record['id'])); ?></td>



    <td><?php echo $this->record['client_code']; ?></td>
    <td><?php echo $this->record['customer_name']; ?></td>
    <td><?php echo $this->record['group_id']; ?></td>
</tr>
