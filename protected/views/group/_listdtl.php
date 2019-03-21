
<tr class='clickable-row' data-href='<?php echo $this->getLink('XR01', 'group/edit', 'group/view', array('index'=>$this->record['id']));?>'>


    <td><?php echo $this->drawEditButton('XR01', 'group/edit', 'group/view', array('index'=>$this->record['id'])); ?></td>



    <td><?php echo $this->record['company_code']; ?></td>
    <td><?php echo $this->record['staff_name']; ?></td>
    <td><?php echo $this->record['assign_date']; ?></td>
    <td><?php echo $this->record['cross_district']; ?></td>
    <td><?php echo $this->record['occurrences']; ?></td>
    <td><?php echo $this->record['salesman_one_ts']; ?></td>
</tr>
