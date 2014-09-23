
<?= Helper\template('task_details', array('task' => $task, 'project' => $project)) ?>

<?= Helper\template('task_show_description', array('task' => $task)) ?>

<?= Helper\template('subtask_show', array('task' => $task, 'subtasks' => $subtasks)) ?>

<?php if (! empty($files)): ?>
<div id="attachments" class="task-show-section">
    <?= Helper\template('file_show', array('task' => $task, 'files' => $files)) ?>
</div>
<?php endif ?>

<?= Helper\template('task_comments', array('task' => $task, 'comments' => $comments)) ?>

<?= Helper\template('comment_create', array('skip_cancel' => true,
                                        'values' => array(
                                            'user_id' => $user_id,
                                            'task_id' => $task['id'],
                                        ),
                                        'errors' => array(),
                                        'task' => $task)); ?>
