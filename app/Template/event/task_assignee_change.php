<?= $this->user->avatar($email, $author, 32) ?>

<p class="activity-title">
    <?php $assignee = $task['assignee_name'] ?: $task['assignee_username'] ?>
    <span class="activity-datetime">
        <?= $this->dt->datetime($date_creation) ?>
    </span>
    <?php if (! empty($assignee)): ?>
        <i class="fa fa-user fa-fw"></i>
        <?= e('%s changed the assignee of the task %s to %s',
                $this->text->e($author),
                $this->url->link(t('#%d', $task['id']), 'task', 'show', array('task_id' => $task['id'], 'project_id' => $task['project_id'])).' <strong>'.$this->text->e($task['title']).'</strong>',
                $this->text->e($assignee)
            ) ?>
    <?php else: ?>
        <i class="fa fa-user-times fa-fw"></i>
        <?= e('%s remove the assignee of the task %s', $this->text->e($author), $this->url->link(t('#%d', $task['id']), 'task', 'show', array('task_id' => $task['id'], 'project_id' => $task['project_id']))) ?>
    <?php endif ?>
</p>
