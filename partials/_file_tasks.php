<?php
// FILE: partials/_file_tasks.php
?>

<section class="form-section">
    <div class="section-header">
        <h2><?php echo __('tasks'); ?> (<?php echo count($data['tasks']); ?>)</h2>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th><?php echo __('due_date'); ?></th>
                    <th><?php echo __('task'); ?></th>
                    <th><?php echo __('details'); ?></th>
                    <th><?php echo __('priority'); ?></th>
                    <th><?php echo __('assigned_employee'); ?></th>
                    <th></th> <!-- Actions -->
                </tr>
            </thead>
            <tbody>
                <?php if ($row_permcheck['admjobs_aperm'] == 1): ?>
                <!-- Row for adding a new task -->
                <tr class="add-new-row">
                    <td><input type="date" name="job_date" class="form-input"></td>
                    <td>
                        <select name="job_name" class="form-input">
                            <option value="">-- <?php echo __('select_task'); ?> --</option>
                            <?php foreach($data['job_names'] as $job): ?>
                                <option value="<?php echo $job['id']; ?>"><?php echo safe_output($job['job_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><textarea name="job_details" class="form-input" rows="1"></textarea></td>
                    <td>
                        <select name="job_priority" class="form-input">
                            <option value="0"><?php echo __('normal'); ?></option>
                            <option value="1"><?php echo __('urgent'); ?></option>
                        </select>
                    </td>
                    <td>
                        <select name="employee_name" class="form-input">
                             <option value="">-- <?php echo __('select_employee'); ?> --</option>
                             <?php foreach($data['researchers'] as $researcher): // Assuming tasks are for researchers ?>
                                <option value="<?php echo $researcher['id']; ?>"><?php echo safe_output($researcher['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td></td>
                </tr>
                <?php endif; ?>

                <!-- Existing Tasks -->
                 <?php if (empty($data['tasks'])): ?>
                    <tr><td colspan="6"><?php echo __('no_tasks_recorded'); ?></td></tr>
                <?php else: ?>
                    <?php foreach($data['tasks'] as $task): ?>
                    <tr class="status-<?php echo $task['task_status']; ?>">
                        <td><?php echo safe_output($task['duedate']); ?></td>
                        <td>
                            <?php 
                                $task_name = 'N/A';
                                foreach($data['job_names'] as $job) {
                                    if ($job['id'] == $task['task_type']) {
                                        $task_name = $job['job_name'];
                                        break;
                                    }
                                }
                                echo safe_output($task_name);
                            ?>
                        </td>
                        <td><?php echo safe_output($task['details']); ?></td>
                        <td><?php echo $task['priority'] == 1 ? '<span class="priority-urgent">' . __('urgent') . '</span>' : __('normal'); ?></td>
                        <td>
                             <?php 
                                $emp_name = 'N/A';
                                foreach($data['researchers'] as $emp) { 
                                    if ($emp['id'] == $task['employee_id']) {
                                        $emp_name = $emp['name'];
                                        break;
                                    }
                                }
                                echo safe_output($emp_name);
                            ?>
                        </td>
                        <td class="actions-cell">
                            <?php if($row_permcheck['admjobs_eperm'] == 1): ?>
                                <a href="?id=<?php echo $fileId; ?>&tid=<?php echo $task['id']; ?>" class="action-btn" title="<?php echo __('edit'); ?>"><i class='bx bx-edit'></i></a>
                            <?php endif; ?>
                            <?php if($row_permcheck['admjobs_dperm'] == 1): ?>
                                <a href="editfile.php?tid=<?php echo $task['id']; ?>&fid=<?php echo $fileId; ?>" class="action-btn delete" onclick="return confirm('<?php echo __('confirm_delete'); ?>')"><i class='bx bx-trash'></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
