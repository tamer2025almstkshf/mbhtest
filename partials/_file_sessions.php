<?php
// FILE: partials/_file_sessions.php
/**
 * Partial view for managing court sessions in the file edit form.
 *
 * Uses variables from FileEdit.php:
 * - $data['sessions'], $data['file_degrees'] (arrays)
 * - $row_permcheck (array)
 * - $fileId (int)
 */
?>

<section class="form-section">
    <div class="section-header">
        <h2>الجلسات (<?php echo count($data['sessions']); ?>)</h2>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>تاريخ الجلسة</th>
                    <th>درجة التقاضي</th>
                    <th>القرار/التفاصيل</th>
                    <th>الرابط</th>
                    <th>حالة الإرفاق</th>
                    <th></th> <!-- Actions -->
                </tr>
            </thead>
            <tbody>
                <?php if ($row_permcheck['session_aperm'] == 1): ?>
                <!-- Row for adding a new session -->
                <tr class="add-new-row">
                    <td><input type="date" name="Hearing_dt" class="form-input" value="<?php echo date('Y-m-d'); ?>"></td>
                    <td>
                        <select name="session_degree" class="form-input">
                            <option value="">-- اختر درجة --</option>
                            <?php foreach($data['file_degrees'] as $degree): ?>
                                <option value="<?php echo safe_output($degree['file_year'].'/'.$degree['case_num'].'-'.$degree['degree']); ?>">
                                    <?php echo safe_output($degree['degree']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><textarea name="session_decission" class="form-input" rows="1"></textarea></td>
                    <td><input type="text" name="link" class="form-input"></td>
                    <td><!-- Status is not editable on add --></td>
                    <td><!-- Add button can be in form footer --></td>
                </tr>
                <?php endif; ?>

                <!-- Existing Sessions -->
                <?php if (empty($data['sessions'])): ?>
                    <tr><td colspan="6">لا توجد جلسات مسجلة.</td></tr>
                <?php else: ?>
                    <?php foreach($data['sessions'] as $session): ?>
                    <tr>
                        <td><?php echo safe_output($session['session_date']); ?></td>
                        <td><?php echo safe_output($session['session_degree']); ?></td>
                        <td><?php echo safe_output($session['session_decission']); ?></td>
                        <td>
                            <?php if (!empty($session['link'])): ?>
                                <a href="<?php echo safe_output($session['link']); ?>" target="_blank">رابط</a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($session['session_note'])): ?>
                                <span class="status-attached">تم الإرفاق</span>
                            <?php else: ?>
                                <span class="status-not-attached">لم يرفق</span>
                            <?php endif; ?>
                        </td>
                        <td class="actions-cell">
                            <?php if($row_permcheck['session_eperm'] == 1): ?>
                                <a href="?id=<?php echo $fileId; ?>&esid=<?php echo $session['session_id']; ?>" class="action-btn" title="تعديل"><i class='bx bx-edit'></i></a>
                            <?php endif; ?>
                            <?php if($row_permcheck['session_dperm'] == 1): ?>
                                <a href="editfile.php?sid=<?php echo $session['session_id']; ?>&fid=<?php echo $fileId; ?>" class="action-btn delete" onclick="return confirm('هل أنت متأكد؟')"><i class='bx bx-trash'></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
