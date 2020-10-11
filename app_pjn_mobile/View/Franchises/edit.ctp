<?php $this->start('franchise_menu');?>
<?php echo $this->element('franchise_menu');?>
<?php $this->end();?>
<a href="/franchises/" class=""> &laquo; Back to franchise list</a><br>

<h1>Edit Franchise: <?php echo $franchiseInfo['Franchise']['name'];?></h1><br>
<?php echo $this->Form->create(); ?>
<table class=" table-condensed" style="width:500px;">
    <tbody>
        <tr>
            <td>Name</td>
            <td><?php echo $this->Form->input('name', array('placeholder'=>'Enter Franchise Name', 'label'=>false, 'required'=>true, 'class'=>'form-control input-sm')); ?></td>
        </tr>
        <tr>
            <td>Status</td>
            <td><?php echo $this->Form->input('is_active', array('type'=>'checkbox', ));?></td>
        </tr>
        <tr>
            <td>Code</td>
            <td><?php echo $this->Form->input('code', array('placeholder'=>'Enter Franchise Code', 'label'=>false, 'class'=>'form-control input-sm')); ?></td>
        </tr>
        <tr>
            <td>Login Pin</td>
            <td><?php echo $this->Form->input('login_pin', array('placeholder'=>'Login Pin', 'label'=>false, 'class'=>'form-control input-sm')); ?></td>
        </tr>
        <tr>
            <td>Mobile No.</td>
            <td><?php echo $this->Form->input('mobile', array('placeholder'=>'Enter Mobile No.', 'label'=>false, 'class'=>'form-control input-sm')); ?></td>
        </tr>
        <tr>
            <td>Email Address</td>
            <td><?php echo $this->Form->input('email', array('placeholder'=>'Enter Email Address', 'label'=>false, 'class'=>'form-control input-sm')); ?></td>
        </tr>
        <tr>
            <td>City</td>
            <td><?php echo $this->Form->input('city', array('placeholder'=>'Enter City', 'label'=>false, 'class'=>'form-control input-sm')); ?></td>
        </tr>
        <tr>
            <td>District</td>
            <td><?php echo $this->Form->input('district', array('placeholder'=>'Enter District', 'label'=>false, 'class'=>'form-control input-sm'));?></td>
        </tr>
        <tr>
            <td>State</td>
            <td><?php echo $this->Form->input('state', array('placeholder'=>'Enter State', 'label'=>false, 'class'=>'form-control input-sm')); ?></td>
        </tr>
        <tr>
            <td>Country</td>
            <td><?php echo $this->Form->input('country', array('placeholder'=>'Enter Country', 'label'=>false, 'class'=>'form-control input-sm')); ?></td>
        </tr>
        <tr>
            <td>Address</td>
            <td><?php echo $this->Form->input('address1', array('placeholder'=>'Enter Address', 'label'=>false, 'class'=>'form-control input-sm')); ?></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center">
                <br>
                <button class="btn btn-primary" type="submit">Update Franchise</button>
                <br><br>
                <a href="/franchises/" class="btn btn-sm btn-warning">Cancel</a>
            </td>
        </tr>
    </tbody>
</table>
<?php echo $this->Form->end(); ?>