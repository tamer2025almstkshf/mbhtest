<?php
// FILE: partials/_file_legal_entities.php
/**
 * Partial view for managing legal entities (courts, police stations, etc.) in the file edit form.
 *
 * Uses variables from FileEdit.php:
 * - $fileDetails (array)
 * - $data['case_types'], $data['police_stations'], $data['prosecutions'], $data['courts'] (arrays)
 */
?>

<section class="form-section">
    <div class="section-header">
        <h2>المحاكم ومراكز الشرطة</h2>
    </div>

    <div class="grid-container">
        <!-- Case Type -->
        <div class="form-group">
            <label for="fctype_edit">نوع القضية</label>
            <select id="fctype_edit" name="fctype_edit" class="form-input">
                <option value="">-- اختر نوع --</option>
                <?php foreach($data['case_types'] as $type): ?>
                    <option value="<?php echo safe_output($type['ct_name']); ?>" <?php if($fileDetails['fcase_type'] === $type['ct_name']) echo 'selected'; ?>>
                        <?php echo safe_output($type['ct_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Police Station -->
        <div class="form-group">
            <label for="fpolice_edit">مركز الشرطة</label>
            <select id="fpolice_edit" name="fpolice_edit" class="form-input">
                 <option value="">-- اختر مركز --</option>
                <?php foreach($data['police_stations'] as $station): ?>
                    <option value="<?php echo safe_output($station['policestation_name']); ?>" <?php if($fileDetails['fpolice_station'] === $station['policestation_name']) echo 'selected'; ?>>
                        <?php echo safe_output($station['policestation_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Prosecution -->
        <div class="form-group">
            <label for="fprosecution2">النيابة</label>
            <select id="fprosecution2" name="fprosecution2" class="form-input">
                 <option value="">-- اختر نيابة --</option>
                <?php foreach($data['prosecutions'] as $prosecution): ?>
                    <option value="<?php echo safe_output($prosecution['prosecution_name']); ?>" <?php if($fileDetails['file_prosecution'] === $prosecution['prosecution_name']) echo 'selected'; ?>>
                        <?php echo safe_output($prosecution['prosecution_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Court -->
        <div class="form-group">
            <label for="fcourt_edit">المحكمة</label>
            <select id="fcourt_edit" name="fcourt_edit" class="form-input">
                 <option value="">-- اختر محكمة --</option>
                <?php foreach($data['courts'] as $court): ?>
                    <option value="<?php echo safe_output($court['court_name']); ?>" <?php if($fileDetails['file_court'] === $court['court_name']) echo 'selected'; ?>>
                        <?php echo safe_output($court['court_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</section>
