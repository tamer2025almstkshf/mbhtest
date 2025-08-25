<?php
// FILE: partials/_file_attachments.php
?>

<section class="form-section">
    <div class="section-header">
        <h2><?php echo __('attachments'); ?> (<?php echo count($data['attachments']); ?>)</h2>
    </div>
    
    <?php if ($row_permcheck['attachments_aperm'] == 1): ?>
    <div class="form-group">
        <label for="attach_files_multi"><?php echo __('add_new_attachments'); ?></label>
        <input type="file" id="attach_files_multi" name="attach_files_multi[]" class="form-input" multiple>
    </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th><?php echo __('file_name'); ?></th>
                    <th><?php echo __('type_size'); ?></th>
                    <th><?php echo __('attachment_date'); ?></th>
                    <th><?php echo __('attached_by'); ?></th>
                    <th></th> <!-- Actions -->
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['attachments'])): ?>
                    <tr><td colspan="5"><?php echo __('no_attachments_found'); ?></td></tr>
                <?php else: ?>
                    <?php foreach($data['attachments'] as $attachment): ?>
                    <tr>
                        <td>
                            <a href="<?php echo safe_output($attachment['attachment']); ?>" target="_blank">
                                <?php echo safe_output(basename($attachment['attachment'])); ?>
                            </a>
                        </td>
                        <td><?php echo safe_output($attachment['attachment_type'] . ' / ' . $attachment['attachment_size']); ?></td>
                        <td><?php echo safe_output($attachment['timestamp']); ?></td>
                        <td>
                            <?php 
                                echo safe_output($attachment['done_by']);
                            ?>
                        </td>
                        <td class="actions-cell">
                             <?php if($row_permcheck['attachments_dperm'] == 1): ?>
                                <a href="fattachdel.php?id=<?php echo $attachment['id']; ?>&fid=<?php echo $fileId; ?>&page=FileEdit.php" class="action-btn delete" onclick="return confirm('<?php echo __('confirm_delete'); ?>')">
                                    <i class='bx bx-trash'></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
