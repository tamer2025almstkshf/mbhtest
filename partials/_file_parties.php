<?php
// FILE: partials/_file_parties.php
?>

<section class="form-section">
    <div class="section-header">
        <h2><?php echo __('parties_data'); ?></h2>
    </div>

    <div class="parties-grid">
        <!-- Clients Column -->
        <div class="party-column">
            <h4><?php echo __('clients'); ?> <button type="button" class="toggle-extra-fields" onclick="toggleExtraFields('clients')">+</button></h4>
            <?php for ($i = 1; $i <= 5; $i++):
                $clientKey = 'file_client' . ($i > 1 ? $i : '');
                $charKey = 'fclient_characteristic' . ($i > 1 ? $i : '');
            ?>
            <div class="form-group-pair <?php echo $i > 1 ? 'extra-client-field' : ''; ?>" style="<?php echo $i > 1 ? 'display:none;' : ''; ?>">
                <div class="form-group">
                    <label for="fclient_edit<?php echo $i; ?>"><?php echo __('client') . ' ' . $i; echo $i == 1 ? ' <span class="required">*</span>' : ''; ?></label>
                    <select id="fclient_edit<?php echo $i; ?>" name="fclient_edit<?php echo $i; ?>" class="form-input">
                        <option value="">-- <?php echo __('select_client'); ?> --</option>
                        <?php foreach ($data['clients'] as $client): ?>
                            <option value="<?php echo $client['id']; ?>" <?php if (($fileDetails[$clientKey] ?? '') == $client['id']) echo 'selected'; ?>>
                                <?php echo $client['id'] . ' # ' . safe_output($client['arname']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fchar_edit<?php echo $i; ?>"><?php echo __('client_status'); ?></label>
                    <select id="fchar_edit<?php echo $i; ?>" name="fchar_edit<?php echo $i; ?>" class="form-input">
                        <option value="">-- <?php echo __('select_status'); ?> --</option>
                        <?php foreach ($data['client_statuses'] as $status): ?>
                            <option value="<?php echo $status['arname']; ?>" <?php if (($fileDetails[$charKey] ?? '') == $status['arname']) echo 'selected'; ?>>
                                <?php echo safe_output($status['arname']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <?php endfor; ?>
        </div>

        <!-- Opponents Column -->
        <div class="party-column">
            <h4><?php echo __('opponents'); ?> <button type="button" class="toggle-extra-fields" onclick="toggleExtraFields('opponents')">+</button></h4>
            <?php for ($i = 1; $i <= 5; $i++):
                $opponentKey = 'file_opponent' . ($i > 1 ? $i : '');
                $charKey = 'fopponent_characteristic' . ($i > 1 ? $i : '');
            ?>
            <div class="form-group-pair <?php echo $i > 1 ? 'extra-opponent-field' : ''; ?>" style="<?php echo $i > 1 ? 'display:none;' : ''; ?>">
                <div class="form-group">
                    <label for="fopponent_edit<?php echo $i; ?>"><?php echo __('opponent') . ' ' . $i; ?></label>
                    <select id="fopponent_edit<?php echo $i; ?>" name="fopponent_edit<?php echo $i; ?>" class="form-input">
                        <option value="">-- <?php echo __('select_opponent'); ?> --</option>
                         <?php foreach ($data['opponents'] as $opponent): ?>
                            <option value="<?php echo $opponent['id']; ?>" <?php if (($fileDetails[$opponentKey] ?? '') == $opponent['id']) echo 'selected'; ?>>
                                <?php echo $opponent['id'] . ' # ' . safe_output($opponent['arname']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fopponent_charedit<?php echo $i; ?>"><?php echo __('opponent_status'); ?></label>
                    <select id="fopponent_charedit<?php echo $i; ?>" name="fopponent_charedit<?php echo $i; ?>" class="form-input">
                        <option value="">-- <?php echo __('select_status'); ?> --</option>
                        <?php foreach ($data['client_statuses'] as $status): ?>
                            <option value="<?php echo $status['arname']; ?>" <?php if (($fileDetails[$charKey] ?? '') == $status['arname']) echo 'selected'; ?>>
                                <?php echo safe_output($status['arname']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <?php endfor; ?>
        </div>
    </div>
</section>
