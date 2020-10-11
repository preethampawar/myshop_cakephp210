<?php
echo $this->Form->create();
echo $this->Form->input('Invoice.invoice_type', ['type' => 'hidden', 'value' => $invoice_type]);
?>
<br>
<div class="row">
    <div class="col-xs-12">
        <table class="table table-condensed table-striped" style="width: 600px;">
            <tbody>
            <tr>
                <td style="width: 200px;">Invoice Date</td>
                <td>
                    <?php
                    echo $this->Form->input('Invoice.invoice_date', array('label' => false, 'required' => true, 'type' => 'date', 'title' => 'Select date'));
                    ?>
                </td>
            </tr>
            <tr>
                <td>Invoice No.</td>
                <td>
                    <?php echo $this->Form->input('Invoice.name', array('label' => false, 'required' => true, 'type' => 'text', 'title' => 'Enter Invoice Name', 'class' => 'form-control input-sm')); ?>
                </td>
            </tr>

            <?php
            if($hasFranchise) {
                ?>
                <tr>
                    <td>Franchise</td>
                    <td>
                        <?php echo $this->Form->input('Invoice.franchise_id', array('label' => false, 'empty' => '-', 'title' => 'Select Franchise', 'options' => $franchiseList, 'type' => 'select', 'class' => 'form-control input-sm')); ?>
                    </td>
                </tr>
                <?php
            }
            ?>

            <tr>
                <td>Discount(%)</td>
                <td>
                    <?php echo $this->Form->input('Invoice.discount', array('label' => false, 'empty' => '-', 'title' => 'Discount in percentage', 'type' => 'number', 'class' => 'form-control input-sm', 'default'=>0, 'min'=>0, 'max'=>100)); ?>
                </td>
            </tr>
            <tr>
                <td>SGST(%)</td>
                <td>
                    <?php echo $this->Form->input('Invoice.sgst', array('label' => false, 'empty' => '-', 'title' => 'SGST in percentage', 'type' => 'number', 'class' => 'form-control input-sm', 'default'=>0, 'min'=>0, 'max'=>100)); ?>
                </td>
            </tr>
            <tr>
                <td>CGST(%)</td>
                <td>
                    <?php echo $this->Form->input('Invoice.cgst', array('label' => false, 'empty' => '-', 'title' => 'CGST in percentage', 'type' => 'number', 'class' => 'form-control input-sm', 'default'=>0, 'min'=>0, 'max'=>100)); ?>
                </td>
            </tr>
            <tr>
                <td>IGST(%)</td>
                <td>
                    <?php echo $this->Form->input('Invoice.igst', array('label' => false, 'empty' => '-', 'title' => 'IGST in percentage', 'type' => 'number', 'class' => 'form-control input-sm', 'default'=>0, 'min'=>0, 'max'=>100)); ?>
                </td>
            </tr>
            <tr>
                <td>Delivery Charges</td>
                <td>
                    <?php echo $this->Form->input('Invoice.delivery_amount', array('label' => false, 'empty' => '-', 'title' => 'Delivery Charges', 'type' => 'number', 'class' => 'form-control input-sm', 'default'=>0, 'min'=>0, 'max'=>100000)); ?>
                </td>
            </tr>
            <tr>
                <td>Total Invoice Amount</td>
                <td>
                    <?php echo $this->Form->input('Invoice.static_invoice_value', array('label' => false, 'empty' => '-', 'title' => 'Total invoice value including transportation', 'type' => 'number', 'class' => 'form-control input-sm', 'default'=>0, 'min'=>0, 'max'=>10000000000)); ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit" class="btn btn-sm btn-primary">Save Invoice</button>
                </td>
            </tr>
            </tbody>
        </table>

    </div>
</div>

<?php
echo $this->Form->end();
?>