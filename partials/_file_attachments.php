<?php
// FILE: partials/_file_attachments.php
/**
 * Partial view for managing attachments in the file edit form.
 *
 * Uses variables from FileEdit.php:
 * - $data['attachments'] (array)
 * - $row_permcheck (array)
 * - $fileId (int)
 */
?>

<section class="form-section">
    <div class="section-header">
        <h2>المرفقات (<?php echo count($data['attachments']); ?>)</h2>
    </div>
    
    <?php if ($row_permcheck['attachments_aperm'] == 1): ?>
    <div class="form-group">
        <label for="attach_files_multi">إضافة مرفقات جديدة</label>
        <input type="file" id="attach_files_multi" name="attach_files_multi[]" class="form-input" multiple>
    </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>اسم الملف</th>
                    <th>النوع / الحجم</th>
                    <th>تاريخ الإرفاق</th>
                    <th>المرفق بواسطة</th>
                    <th></th> <!-- Actions -->
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['attachments'])): ?>
                    <tr><td colspan="5">لا توجد مرفقات.</td></tr>
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
                                // This requires a full user list to be efficient, or another query.
                                // For now, we assume user data might be joined in the main query in the future.
                                echo safe_output($attachment['done_by']); // Displaying ID for now
                            ?>
                        </td>
                        <td class="actions-cell">
                             <?php if($row_permcheck['attachments_dperm'] == 1): ?>
                                <a href="fattachdel.php?id=<?php echo $attachment['id']; ?>&fid=<?php echo $fileId; ?>&page=FileEdit.php" class="action-btn delete" onclick="return confirm('هل أنت متأكد؟')">
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
