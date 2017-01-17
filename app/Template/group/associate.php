<div class="page-header">
    <h2><?= t('Add group member to "%s"', $group['name']) ?></h2>
</div>
<?php if (empty($users)): ?>
    <p class="alert"><?= t('There is no user available.') ?></p>
<?php else: ?>
    <form method="post" action="<?= $this->url->href('GroupListController', 'addUser', array('group_id' => $group['id'])) ?>" autocomplete="off">
        <?= $this->form->csrf() ?>
        <?= $this->form->hidden('group_id', $values) ?>

        <?= $this->form->label(t('User'), 'user_id') ?>
        <?= $this->app->component('select-dropdown-autocomplete', array(
            'name' => 'user_id',
            'items' => $users,
            'defaultValue' => isset($values['user_id']) ? $values['user_id'] : null,
        )) ?>

        <?= $this->modal->submitButtons() ?>
    </form>
<?php endif ?>
