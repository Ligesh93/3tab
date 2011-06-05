<?php
// auto-generated by sfPropelCrud
// date: 2008/06/03 03:45:14
?>
<?php use_helper('Object') ?>

<?php echo form_tag('venue/update') ?>

<?php echo object_input_hidden_tag($venue, 'getId') ?>
<h1>Create/Edit Venue</h1>
<hr />

<table id="form">
<tbody>
<tr>
  <th>Name <span class="red">*</span> :</th>
  <td><?php echo object_input_tag($venue, 'getName', array (
  'size' => 20,
)) ?></td>
</tr>
<tr>
  <th>Active <span class="red"*</span> :</th>
  <td><?php echo object_checkbox_tag($venue, 'getActive', array (
)) ?></td>
</tr>
<tr>
  <th>Priority <span class="red">*</span> :</th>
  <td><?php echo object_input_tag($venue, 'getPriority', array (
  'size' => 5,
)) ?></td>
</tr>
</tbody>
</table>
<hr />

<div id="button">
<?php echo submit_tag('Save') ?>
<?php if ($venue->getId()): ?>
  &nbsp;<?php echo link_to('Delete', 'venue/delete?id='.$venue->getId(), 'post=true&confirm=Are you sure?') ?>
  &nbsp;<?php echo link_to('Cancel', 'venue/show?id='.$venue->getId()) ?>
<?php else: ?>
  &nbsp;<?php echo link_to('Cancel', 'venue/list') ?>
<?php endif; ?>
</div>
</form>
