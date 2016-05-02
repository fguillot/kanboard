<?php if (! empty($files)): ?>
    <table class="table-stripped table-small">
        <tr>
            <th><?= t('Filename') ?></th>
            <th><?= t('Creator') ?></th>
            <th><?= t('Date') ?></th>
            <th><?= t('Size') ?></th>
        </tr>
        <?php foreach ($files as $file): ?>
            <tr>
                <td>
                    <i class="fa <?= $this->file->icon($file['name']) ?> fa-fw"></i>
                    <div class="dropdown">
                        <a href="#" class="dropdown-menu dropdown-menu-link-text"><?= $this->text->e($file['name']) ?> <i class="fa fa-caret-down"></i></a>
                        <ul>
                            <?php if ($this->file->getPreviewType($file['name']) !== null): ?>
                                <li>
                                    <?= $this->url->button('fa-eye', t('View file'), 'FileViewer', 'show', array('task_id' => $task['id'], 'project_id' => $task['project_id'], 'file_id' => $file['id']), false, 'popover') ?>
                                </li>
                            <?php endif ?>
                            <li>
                                <?= $this->url->button('fa-download', t('Download'), 'FileViewer', 'download', array('task_id' => $task['id'], 'project_id' => $task['project_id'], 'file_id' => $file['id'])) ?>
                            </li>
                            <?php if ($this->user->hasProjectAccess('TaskFile', 'remove', $task['project_id'])): ?>
                                <li>
                                    <?= $this->url->button('fa-trash', t('Remove'), 'TaskFile', 'confirm', array('task_id' => $task['id'], 'project_id' => $task['project_id'], 'file_id' => $file['id']), false, 'popover') ?>
                                </li>
                            <?php endif ?>
                        </ul>
                    </div>
                </td>
                <td>
                    <?= $this->text->e($file['user_name'] ?: $file['username']) ?>
                </td>
                <td>
                    <?= $this->dt->date($file['date']) ?>
                </td>
                <td>
                    <?= $this->text->bytes($file['size']) ?>
                </td>
            </tr>
        <?php endforeach ?>
    </table>
<?php endif ?>
