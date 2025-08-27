<?php
// FILE: partials/_file_details.php

/** @var array $data */
/** @var array $row_permcheck */
/** @var array $fileDetails */
/** @var int|string $fileId */
?>

<section class="form-section">
    <div class="section-header">
        <h2><?php echo __('file_data'); ?></h2>
        <div class="header-actions">
            <?php if ($row_permcheck['levels_eperm'] == 1): ?>
                <button type="button" class="action-btn" onclick="openPopup('Fees.php?id=<?php echo safe_output($fileId); ?>')">
                    <i class='bx bx-sitemap'></i> <?php echo __('case_stages'); ?>
                </button>
            <?php endif; ?>
            <button type="button" class="action-btn" onclick="openPopup('relatedfiles.php?mfid=<?php echo safe_output($fileId); ?>')">
                <i class='bx bx-link'></i> <?php echo __('related_files'); ?>
            </button>
        </div>
    </div>

    <div class="grid-container">
        <!-- File Number and Importance -->
        <div class="form-group">
            <label><?php echo __('file_number'); ?></label>
            <div class="file-id-display">
                <?php
                    function getFilePrefix($place) {
                        $map = ['الشارقة' => 'SHJ', 'دبي' => 'DXB', 'عجمان' => 'AJM'];
                        return $map[$place] ?? '';
                    }
                    echo getFilePrefix($fileDetails['frelated_place']) . ' ' . safe_output($fileId);
                ?>
            </div>
            <div class="form-check mt-2">
                <input type="checkbox" name="important" id="important" value="1" class="form-check-input" <?php if(!empty($fileDetails['important'])) echo 'checked'; ?>>
                <label for="important" class="form-check-label"><?php echo __('register_as_important'); ?></label>
            </div>
        </div>

        <!-- File Classification -->
        <div class="form-group">
            <label for="fclass_edit"><?php echo __('file_classification'); ?> <span class="required">*</span></label>
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
            <label for="place_edit"><?php echo __('responsible_branch'); ?></label>
            <select name="place_edit" id="place_edit" class="form-input">
                <option value="الشارقة" <?php if($fileDetails['frelated_place'] === 'الشارقة') echo 'selected'; ?>><?php echo __('sharjah'); ?></option>
                <option value="دبي" <?php if($fileDetails['frelated_place'] === 'دبي') echo 'selected'; ?>><?php echo __('dubai'); ?></option>
                <option value="عجمان" <?php if($fileDetails['frelated_place'] === 'عجمان') echo 'selected'; ?>><?php echo __('ajman'); ?></option>
            </select>
        </div>

        <!-- File Status -->
        <div class="form-group">
            <label><?php echo __('file_status'); ?></label>
            <div class="radio-group">
                <label><input type="radio" name="fstatus_edit" value="في الانتظار" <?php if($fileDetails['file_status'] === 'في الانتظار') echo 'checked'; ?>> <?php echo __('pending'); ?></label>
                <label><input type="radio" name="fstatus_edit" value="متداول" <?php if($fileDetails['file_status'] === 'متداول') echo 'checked'; ?>> <?php echo __('active'); ?></label>
                <label><input type="radio" name="fstatus_edit" value="مؤرشف" <?php if($fileDetails['file_status'] === 'مؤرشف') echo 'checked'; ?>> <?php echo __('archived'); ?></label>
            </div>
        </div>

        <!-- Subject -->
        <div class="form-group full-width">
            <label for="fsubject_edit"><?php echo __('subject'); ?></label>
            <textarea id="fsubject_edit" name="fsubject_edit" class="form-input" rows="2"><?php echo safe_output($fileDetails['file_subject']); ?></textarea>
        </div>

        <!-- Notes -->
        <div class="form-group full-width">
            <label for="fnotes_edit"><?php echo __('notes'); ?></label>
            <textarea id="fnotes_edit" name="fnotes_edit" class="form-input" rows="2"><?php echo safe_output($fileDetails['file_notes']); ?></textarea>
        </div>
    </div>
</section>
