<details class="accordion-section" <?= empty($task['description']) ? '' : 'open' ?>>
    <summary class="accordion-title"><?= t('Description') ?></summary>
    <div class="accordion-content">
        <article class="markdown" dir="auto">
            <?= $this->text->markdown($task['description'], isset($is_public) && $is_public) ?>
        </article>
    </div>
</details>
