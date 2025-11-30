<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', __('Installation successful'));

?>

<div class="install">
<a style="color:red;" href="https://bit.ly/3Uzh8xZ" target="_blank">Web Community</a>
    <div class="text-center">
        <a href="<?= $this->Url->build('/'); ?>" class="btn btn-primary"><?= __('Access Home') ?></a>
        <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'signin', 'prefix' => 'auth']); ?>"
           class="btn btn-success"><?= __('Access Dashboard') ?></a>
    </div>
</div>
