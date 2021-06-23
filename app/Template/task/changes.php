<?php if (! empty($changes)): ?>
    <ul>
        <?php

        foreach ($changes as $field => $value) {
            switch ($field) {
                case 'title':
                    echo '<li>'.t('New title: %s', $task['title']).'</li>';
                    break;
                case 'owner_id':
                    if (empty($task['owner_id'])) {
                        echo '<li>'.t('The task is not assigned anymore').'</li>';
                    } else {
                        echo '<li>'.t('New assignee: %s', $task['assignee_name'] ?: $task['assignee_username']).'</li>';
                    }
                    break;
                case 'category_id':
                    if (empty($task['category_id'])) {
                        echo '<li>'.t('There is no category now').'</li>';
                    } else {
                        echo '<li>'.t('New category: %s', $task['category_name']).'</li>';
                    }
                    break;
                case 'color_id':
                    echo '<li>'.t('New color: %s', $this->text->in($task['color_id'], $this->task->getColors())).'</li>';
                    break;
                case 'score':
                    echo '<li>'.t('New complexity: %d', $task['score']).'</li>';
                    break;
                case 'date_due':
                    if (empty($task['date_due'])) {
                        echo '<li>'.t('The due date has been removed').'</li>';
                    } else {
                        echo '<li>'.t('New due date: ').$this->dt->datetime($task['date_due']).'</li>';
                    }
                    break;
                case 'description':
                    if (empty($task['description'])) {
                        echo '<li>'.t('There is no description anymore').'</li>';
                    }
                    break;
                case 'recurrence_status':
                case 'recurrence_trigger':
                case 'recurrence_factor':
                case 'recurrence_timeframe':
                case 'recurrence_basedate':
                case 'recurrence_parent':
                case 'recurrence_child':
                    echo '<li>'.t('Recurrence settings has been modified').'</li>';
                    break;
                case 'time_spent':
                    echo '<li>'.t('Time spent changed: %sh', $task['time_spent']).'</li>';
                    break;
                case 'time_estimated':
                    echo '<li>'.t('Time estimated changed: %sh', $task['time_estimated']).'</li>';
                    break;
                case 'date_started':
                    if ($value != 0) {
                        echo '<li>'.t('Start date changed: ').$this->dt->datetime($task['date_started']).'</li>';
                    }
                    break;
                default:
                    echo '<li>'.t('The field "%s" has been updated', $field).'</li>';
            }
        }

        ?>
    </ul>

    <?php if (! empty($changes['description'])): ?>
        <p><strong><?= t('The description has been modified:') ?></strong></p>
        <?php if (isset($public)): ?>
            <div class="markdown" dir="auto"><?= $this->text->markdown($task['description'], true) ?></div>
        <?php else: ?>
            <div class="markdown" dir="auto"><?= $this->text->markdown($task['description']) ?></div>
        <?php endif ?>
    <?php endif ?>
<?php endif ?>