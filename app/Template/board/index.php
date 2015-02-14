<section id="main">

    <?= $this->render('board/filters', array(
        'categories' => $categories,
        'users' => $users,
        'project' => $project,
    )) ?>
    <label><input type="checkbox" id="compactviewtoggler" /><?= t("Compact View") ?></label>
    <?= $this->render('board/show', array(
            'project' => $project,
            'swimlanes' => $swimlanes,
            'categories' => $categories,
            'board_private_refresh_interval' => $board_private_refresh_interval,
            'board_highlight_period' => $board_highlight_period,
    )) ?>

</section>
