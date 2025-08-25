<?php
// FILE: partials/_file_legal_entities.php
?>

<section class="form-section">
    <div class="section-header">
        <h2><?php echo __('courts_and_police_stations'); ?></h2>
    </div>

    <div class="grid-container">
        <!-- Case Type -->
        <div class="form-group">
            <label for="fctype_edit"><?php echo __('case_type'); ?></label>
            <select id="fctype_edit" name="fctype_edit" class="form-input">
                <option value="">-- <?php echo __('select_type'); ?> --</option>
                <?php foreach($data['case_types'] as $type): ?>
                    <option value="<?php echo safe_output($type['ct_name']); ?>" <?php if($fileDetails['fcase_type'] === $type['ct_name']) echo 'selected'; ?>>
                        <?php echo safe_output($type['ct_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Police Station -->
        <div class="form-group">
            <label for="fpolice_edit"><?php echo __('police_station'); ?></label>
            <select id="fpolice_edit" name="fpolice_edit" class="form-input">
                 <option value="">-- <?php echo __('select_station'); ?> --</option>
                <?php foreach($data['police_stations'] as $station): ?>
                    <option value="<?php echo safe_output($station['policestation_name']); ?>" <?php if($fileDetails['fpolice_station'] === $station['policestation_name']) echo 'selected'; ?>>
                        <?php echo safe_output($station['policestation_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Prosecution -->
        <div class="form-group">
            <label for="fprosecution2"><?php echo __('prosecution'); ?></label>
            <select id="fprosecution2" name="fprosecution2" class="form-input">
                 <option value="">-- <?php echo __('select_prosecution'); ?> --</option>
                <?php foreach($data['prosecutions'] as $prosecution): ?>
                    <option value="<?php echo safe_output($prosecution['prosecution_name']); ?>" <?php if($fileDetails['file_prosecution'] === $prosecution['prosecution_name']) echo 'selected'; ?>>
                        <?php echo safe_output($prosecution['prosecution_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Court -->
        <div class="form-group">
            <label for="fcourt_edit"><?php echo __('court'); ?></label>
            <select id="fcourt_edit" name="fcourt_edit" class="form-input">
                 <option value="">-- <?php echo __('select_court'); ?> --</option>
                <?php foreach($data['courts'] as $court): ?>
                    <option value="<?php echo safe_output($court['court_name']); ?>" <?php if($fileDetails['file_court'] === $court['court_name']) echo 'selected'; ?>>
                        <?php echo safe_output($court['court_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</section>
