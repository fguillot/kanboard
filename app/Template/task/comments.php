<?php if (! empty($comments)): ?>
<div id="comments" class="task-show-section <?= $avatars ?: "no-avatars" ?>">
    <div class="page-header">
        <h2>
            <?= t('Comments') ?>
            <span class="comment-sorting">
                <i class="fa fa-sort"></i>
                <?= $this->url->link(t('change sorting'), 'comment', 'toggleSorting', array('task_id' => $task['id'], 'project_id' => $task['project_id'])) ?>
            </span>
        </h2>
    </div>
    <?php if ($editable && $commentsSorting == "DESC"): ?>
        <?= $this->render('comment/create', array(
            'skip_cancel' => true,
            'values' => array(
                'user_id' => $this->user->getId(),
                'task_id' => $task['id'],
            ),
            'errors' => array(),
            'task' => $task,
            'user' => $this->user
        )) ?>
    <?php endif ?>
    
    <?php foreach ($comments as $comment): ?>
        <?= $this->render('comment/show', array(
            'comment' => $comment,
            'task' => $task,
            'project' => $project,
            'editable' => $editable,
            'is_public' => isset($is_public) && $is_public,
        )) ?>
    <?php endforeach ?>

    <?php if ($editable && $commentsSorting == "ASC"): ?>
        <?= $this->render('comment/create', array(
            'skip_cancel' => true,
            'values' => array(
                'user_id' => $this->user->getId(),
                'task_id' => $task['id'],
            ),
            'errors' => array(),
            'task' => $task,
            'user' => $this->user
        )) ?>
    <?php endif ?>
</div>
<?php endif ?>
