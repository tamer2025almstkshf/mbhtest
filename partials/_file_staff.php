<?php
// FILE: partials/_file_staff.php

/** @var array $data */
/** @var array $fileDetails */
?>

<section class="form-section">
    <div class="section-header">
        <h2><?php echo __('file_responsible'); ?></h2>
    </div>

    <div class="grid-container">
        <!-- Secretaries -->
        <div class="form-group">
            <label for="fsc_edit"><?php echo __('secretary'); ?></label>
            <select id="fsc_edit" name="fsc_edit" class="form-input">
                <option value="">-- <?php echo __('select'); ?> --</option>
                <?php foreach($data['secretaries'] as $staff): ?>
                    <option value="<?php echo $staff['id']; ?>" <?php if($fileDetails['file_secritary'] == $staff['id']) echo 'selected'; ?>>
                        <?php echo safe_output($staff['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Legal Researchers -->
        <div class="form-group">
            <label for="fresearcher_edit"><?php echo __('legal_researcher'); ?></label>
            <select id="fresearcher_edit" name="fresearcher_edit" class="form-input">
                <option value="">-- <?php echo __('select'); ?> --</option>
                <?php foreach($data['researchers'] as $staff): ?>
                    <option value="<?php echo $staff['id']; ?>" <?php if($fileDetails['flegal_researcher'] == $staff['id']) echo 'selected'; ?>>
                        <?php echo safe_output($staff['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Legal Advisors -->
        <div class="form-group">
            <label for="fadvisor_edit"><?php echo __('legal_advisor'); ?></label>
            <select id="fadvisor_edit" name="fadvisor_edit" class="form-input">
                <option value="">-- <?php echo __('select'); ?> --</option>
                <?php foreach($data['advisors'] as $staff): ?>
                    <option value="<?php echo $staff['id']; ?>" <?php if($fileDetails['flegal_advisor'] == $staff['id']) echo 'selected'; ?>>
                        <?php echo safe_output($staff['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Lawyers -->
        <div class="form-group">
            <label for="file_lawyer"><?php echo __('lawyer'); ?></label>
            <select id="file_lawyer" name="file_lawyer" class="form-input">
                <option value="">-- <?php echo __('select'); ?> --</option>
                <?php foreach($data['lawyers'] as $staff): ?>
                    <option value="<?php echo $staff['id']; ?>" <?php if($fileDetails['file_lawyer'] == $staff['id']) echo 'selected'; ?>>
                        <?php echo safe_output($staff['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</section>
