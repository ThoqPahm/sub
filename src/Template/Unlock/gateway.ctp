<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Link $link
 */
$this->assign('title', $link->title ?: __('Security Check'));
?>

<div class="container" style="margin-top: 50px; max-width: 600px;">
    <div class="panel panel-default box-shadow">
        <div class="panel-heading bg-primary text-white">
            <h3 class="panel-title text-center" style="color: #fff;"><?= __('Security Check') ?></h3>
        </div>
        <div class="panel-body text-center" style="padding: 30px;">
            <h4><?= __('Please complete the security check to proceed.') ?></h4>
            <p class="text-muted"><?= __('This step is to ensure you are not a robot.') ?></p>
            <hr>

            <?= $this->Form->create(null) ?>

            <div class="form-group" style="margin-bottom: 20px;">
                <!-- Captcha Display -->
                <?php if ($this->Captcha): ?>
                    <?= $this->Captcha->create('security_check', [
                        'type' => 'image',
                        'theme' => 'default'
                    ]) ?>
                <?php else: ?>
                    <p class="text-danger">Captcha component not loaded.</p>
                <?php endif; ?>
            </div>

            <?= $this->Form->button(__('Continue'), ['class' => 'btn btn-success btn-lg btn-block']) ?>

            <?= $this->Form->end() ?>
        </div>
        <div class="panel-footer text-center text-muted">
            <small>&copy; <?= date('Y') ?> <?= __('Sub2Unlock') ?></small>
        </div>
    </div>
</div>

<style>
    .box-shadow {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .captcha-image {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px;
    }
</style>