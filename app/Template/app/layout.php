<section id="main">
    <div class="page-header page-header-mobile">
        <ul class="btn-group">
            <?php if ($this->user->hasAccess('ProjectCreation', 'create')): ?>
                <li>
                    <?= $this->url->button('plus', t('New project'), 'ProjectCreation', 'create', array(), false, 'popover') ?>
                </li>
            <?php endif ?>
            <?php if ($this->app->config('disable_private_project', 0) == 0): ?>
            <li>
                <?= $this->url->button('lock', t('New private project'), 'ProjectCreation', 'createPrivate', array(), false, 'popover') ?>
            </li>
            <?php endif ?>
            <li>
                <?= $this->url->button('search', t('Search'), 'search', 'index') ?>
            </li>
            <li>
                <?= $this->url->button('folder', t('Project management'), 'project', 'index') ?>
            </li>
        </ul>
    </div>
    <section class="sidebar-container" id="dashboard">
        <?= $this->render($sidebar_template, array('user' => $user)) ?>
        <div class="sidebar-content">
            <?= $content_for_sublayout ?>
        </div>
    </section>
</section>
