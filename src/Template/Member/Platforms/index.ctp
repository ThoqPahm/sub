<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Platform[]|\Cake\Collection\CollectionInterface $customPlatforms
 * @var \App\Model\Entity\Platform[]|\Cake\Collection\CollectionInterface $systemPlatforms
 */
$this->assign('title', __('My Platforms'));
$this->assign('content_title', __('My Platforms'));
?>

<div class="row">
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?= __('My Custom Platforms') ?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                        data-target="#addPlatformModal">
                        <i class="fa fa-plus"></i> <?= __('New Platform') ?>
                    </button>
                </div>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($customPlatforms->isEmpty()): ?>
                            <tr>
                                <td colspan="3" class="text-center"><?= __('No custom platforms found.') ?></td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($customPlatforms as $platform): ?>
                                <tr>
                                    <td><?= h($platform->name) ?></td>
                                    <td><?= $platform->created->i18nFormat() ?></td>
                                    <td>
                                        <?= $this->Form->postLink(
                                            __('Delete'),
                                            ['action' => 'delete', $platform->id],
                                            ['confirm' => __('Are you sure you want to delete {0}?', $platform->name), 'class' => 'btn btn-danger btn-xs']
                                        ) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><?= __('System Platforms') ?></h3>
            </div>
            <div class="box-body">
                <ul>
                    <?php foreach ($systemPlatforms as $platform): ?>
                        <li><?= h($platform->name) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Add Platform Modal -->
<div class="modal fade" id="addPlatformModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?= $this->Form->create(null, ['url' => ['action' => 'add']]) ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?= __('Add Custom Platform') ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <?= $this->Form->control('name', ['class' => 'form-control', 'label' => __('Platform Name'), 'required' => true]) ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                <?= $this->Form->button(__('Save'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>