<?php
// FILE: partials/_file_degrees.php

/** @var array $data */
/** @var array $row_permcheck */
/** @var int|string $fileId */
?>

<section class="form-section">
    <div class="section-header">
        <h2><?php echo __('litigation_degrees'); ?> (<?php echo count($data['file_degrees']); ?>)</h2>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th><?php echo __('degree'); ?></th>
                    <th><?php echo __('case_number'); ?></th>
                    <th><?php echo __('year'); ?></th>
                    <th><?php echo __('client_status'); ?></th>
                    <th><?php echo __('opponent_status'); ?></th>
                    <th><?php echo __('entry_date'); ?></th>
                    <th></th> <!-- Actions -->
                </tr>
            </thead>
            <tbody>
                <?php if ($row_permcheck['degree_aperm'] == 1): ?>
                <!-- Row for adding a new degree -->
                <tr class="add-new-row">
                    <td>
                        <select name="fdegree_edit" class="form-input">
                             <option value="">-- <?php echo __('select'); ?> --</option>
                            <?php foreach($data['degrees'] as $degree): ?>
                                <option value="<?php echo safe_output($degree['degree_name']); ?>"><?php echo safe_output($degree['degree_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><input type="number" name="fcaseno_edit" class="form-input"></td>
                    <td><input type="number" name="fyear_edit" class="form-input"></td>
                    <td>
                        <select name="fccharacteristic_edit" class="form-input">
                            <option value="">-- <?php echo __('select'); ?> --</option>
                             <?php foreach($data['client_statuses'] as $status): ?>
                                <option value="<?php echo safe_output($status['arname']); ?>"><?php echo safe_output($status['arname']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                         <select name="focharacteristic_edit" class="form-input">
                            <option value="">-- <?php echo __('select'); ?> --</option>
                             <?php foreach($data['client_statuses'] as $status): ?>
                                <option value="<?php echo safe_output($status['arname']); ?>"><?php echo safe_output($status['arname']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><?php echo date("Y-m-d"); ?></td>
                    <td><!-- Add button can be placed here or in the form footer --></td>
                </tr>
                <?php endif; ?>

                <!-- Existing Degrees -->
                <?php if (empty($data['file_degrees'])): ?>
                    <tr><td colspan="7"><?php echo __('no_litigation_degrees_recorded'); ?></td></tr>
                <?php else: ?>
                    <?php foreach($data['file_degrees'] as $degree): ?>
                    <tr>
                        <td><?php echo safe_output($degree['degree']); ?></td>
                        <td><?php echo safe_output($degree['case_num']); ?></td>
                        <td><?php echo safe_output($degree['file_year']); ?></td>
                        <td><?php echo safe_output($degree['client_characteristic']); ?></td>
                        <td><?php echo safe_output($degree['opponent_characteristic']); ?></td>
                        <td><?php echo safe_output($degree['timestamp']); ?></td>
                        <td class="actions-cell">
                            <?php if($row_permcheck['degree_dperm'] == 1): ?>
                                <a href="editfile.php?diddel=<?php echo $degree['id']; ?>&fid=<?php echo $fileId; ?>" class="action-btn delete" onclick="return confirm('<?php echo __('confirm_delete'); ?>')"><i class='bx bx-trash'></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
