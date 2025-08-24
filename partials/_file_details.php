<?php
// FILE: partials/_file_details.php
/**
 * Partial view for displaying the main details section of the file edit form.
 *
 * This partial is included in FileEdit.php and uses variables defined in that file.
 * Specifically, it uses:
 * - $fileDetails (array): The main data for the file being edited.
 * - $data['file_classes'] (array): A list of all available file classifications.
 * - $row_permcheck (array): The permissions array for the current user.
 * - $fileId (int): The ID of the current file.
 */
?>

<section class="form-section">
    <div class="section-header">
        <h2>بيانات الملف</h2>
        <div class="header-actions">
            <?php if ($row_permcheck['levels_eperm'] == 1): ?>
                <button type="button" class="action-btn" onclick="openPopup('Fees.php?id=<?php echo safe_output($fileId); ?>')">
                    <i class='bx bx-sitemap'></i> مراحل الدعوى
                </button>
            <?php endif; ?>
            <button type="button" class="action-btn" onclick="openPopup('relatedfiles.php?mfid=<?php echo safe_output($fileId); ?>')">
                <i class='bx bx-link'></i> الملفات المرتبطة
            </button>
        </div>
    </div>

    <div class="grid-container">
        <!-- File Number and Importance -->
        <div class="form-group">
            <label>رقم الملف</label>
            <div class="file-id-display">
                <?php
                    // Helper function to get file prefix based on location
                    function getFilePrefix($place) {
                        $map = ['الشارقة' => 'SHJ', 'دبي' => 'DXB', 'عجمان' => 'AJM'];
                        return $map[$place] ?? '';
                    }
                    echo getFilePrefix($fileDetails['frelated_place']) . ' ' . safe_output($fileId);
                ?>
            </div>
            <div class="form-check mt-2">
                <input type="checkbox" name="important" id="important" value="1" class="form-check-input" <?php if(!empty($fileDetails['important'])) echo 'checked'; ?>>
                <label for="important" class="form-check-label">تسجيل كدعوى مهمة</label>
            </div>
        </div>

        <!-- File Classification -->
        <div class="form-group">
            <label for="fclass_edit">تصنيف الملف <span class="required">*</span></label>
            <select name="fclass_edit" id="fclass_edit" class="form-input">
                <?php foreach ($data['file_classes'] as $class): ?>
                    <option value="<?php echo safe_output($class['class_name']); ?>" <?php if ($fileDetails['file_class'] === $class['class_name']) echo 'selected'; ?>>
                        <?php echo safe_output($class['class_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Branch -->
        <div class="form-group">
            <label for="place_edit">الفرع المختص</label>
            <select name="place_edit" id="place_edit" class="form-input">
                <option value="الشارقة" <?php if($fileDetails['frelated_place'] === 'الشارقة') echo 'selected'; ?>>الشارقة</option>
                <option value="دبي" <?php if($fileDetails['frelated_place'] === 'دبي') echo 'selected'; ?>>دبي</option>
                <option value="عجمان" <?php if($fileDetails['frelated_place'] === 'عجمان') echo 'selected'; ?>>عجمان</option>
            </select>
        </div>

        <!-- File Status -->
        <div class="form-group">
            <label>حالة الملف</label>
            <div class="radio-group">
                <label><input type="radio" name="fstatus_edit" value="في الانتظار" <?php if($fileDetails['file_status'] === 'في الانتظار') echo 'checked'; ?>> في الانتظار</label>
                <label><input type="radio" name="fstatus_edit" value="متداول" <?php if($fileDetails['file_status'] === 'متداول') echo 'checked'; ?>> متداول</label>
                <label><input type="radio" name="fstatus_edit" value="مؤرشف" <?php if($fileDetails['file_status'] === 'مؤرشف') echo 'checked'; ?>> مؤرشف</label>
            </div>
        </div>

        <!-- Subject -->
        <div class="form-group full-width">
            <label for="fsubject_edit">الموضوع</label>
            <textarea id="fsubject_edit" name="fsubject_edit" class="form-input" rows="2"><?php echo safe_output($fileDetails['file_subject']); ?></textarea>
        </div>

        <!-- Notes -->
        <div class="form-group full-width">
            <label for="fnotes_edit">الملاحظات</label>
            <textarea id="fnotes_edit" name="fnotes_edit" class="form-input" rows="2"><?php echo safe_output($fileDetails['file_notes']); ?></textarea>
        </div>
    </div>
</section>
