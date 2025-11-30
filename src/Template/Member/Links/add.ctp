<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Link $link
 */
$this->assign('title', __('New Short Link'));
$this->assign('description', __('Create a new shortened link with unlock tasks.'));
$this->assign('content_title', __('New Short Link'));
?>

<div class="box box-primary">
    <div class="box-body">
        <?= $this->Form->create($link, ['id' => 'shorten-form']) ?>

        <div class="form-group">
            <?= $this->Form->control('url', [
                'label' => __('Your URL'),
                'class' => 'form-control',
                'placeholder' => 'http://example.com',
                'required' => true
            ]) ?>
        </div>

        <div class="form-group">
            <?= $this->Form->control('alias', [
                'label' => __('Custom Alias (Optional)'),
                'class' => 'form-control',
                'placeholder' => __('Leave empty for random alias')
            ]) ?>
        </div>

        <div class="form-group">
            <?= $this->Form->control('title', [
                'label' => __('Title (Optional)'),
                'class' => 'form-control'
            ]) ?>
        </div>

        <hr>
        <h4><?= __('Unlock Tasks') ?></h4>
        <p class="text-muted"><?= __('Add tasks that visitors must complete to unlock the link.') ?></p>

        <div id="tasks-container">
            <!-- Tasks will be added here via JS -->
        </div>

        <button type="button" class="btn btn-info btn-sm" id="add-task-btn">
            <i class="fa fa-plus"></i> <?= __('Add Another Step') ?>
        </button>

        <hr>

        <?= $this->Form->button(__('Shorten'), ['class' => 'btn btn-primary btn-lg btn-block']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>

<!-- Templates for JS -->
<script type="text/template" id="task-template">
    <div class="task-item panel panel-default" data-index="{index}" style="margin-bottom: 15px; border: 1px solid #ddd; padding: 10px;">
        <div class="panel-heading" style="background: #f5f5f5; padding: 5px 10px; margin: -10px -10px 10px -10px; border-bottom: 1px solid #ddd;">
            <h3 class="panel-title" style="font-size: 14px; font-weight: bold;">
                <?= __('Step') ?> <span class="step-number">{step}</span>
                <button type="button" class="btn btn-danger btn-xs pull-right remove-task-btn">
                    <i class="fa fa-minus"></i> <?= __('Remove') ?>
                </button>
            </h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?= __('Platform') ?></label>
                        <select name="unlock_tasks[{index}][platform_id]" class="form-control platform-select" required>
                            <option value=""><?= __('Select Platform') ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?= __('Action') ?></label>
                        <select name="unlock_tasks[{index}][platform_action_id]" class="form-control action-select" required>
                            <option value=""><?= __('Select Action') ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><?= __('URL') ?></label>
                        <input type="url" name="unlock_tasks[{index}][platform_url]" class="form-control" placeholder="http://youtube.com/..." required>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>

<!-- Add Platform Modal -->
<div class="modal fade" id="addPlatformModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?= __('Add Custom Platform') ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label><?= __('Platform Name') ?></label>
                    <input type="text" class="form-control" id="new-platform-name">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                <button type="button" class="btn btn-primary" id="save-platform-btn"><?= __('Save') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Add Action Modal -->
<div class="modal fade" id="addActionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?= __('Add Custom Action') ?></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="new-action-platform-id">
                <div class="form-group">
                    <label><?= __('Action Name') ?></label>
                    <input type="text" class="form-control" id="new-action-name">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel') ?></button>
                <button type="button" class="btn btn-primary" id="save-action-btn"><?= __('Save') ?></button>
            </div>
        </div>
    </div>
</div>

<?php $this->start('scriptBottom'); ?>
<script>
    var platforms = <?= json_encode($platforms) ?>;
    var taskCount = 0;
    var currentSelectElement = null;

    function renderPlatformOptions(selectElement, selectedId = null) {
        var html = '<option value=""><?= __('Select Platform') ?></option>';

        html += '<optgroup label="<?= __('System Platforms') ?>">';
        platforms.forEach(function (p) {
            if (p.is_system) {
                var selected = (selectedId && p.id == selectedId) ? 'selected' : '';
                html += '<option value="' + p.id + '" ' + selected + '>📌 ' + p.name + '</option>';
            }
        });
        html += '</optgroup>';

        html += '<optgroup label="<?= __('My Platforms') ?>">';
        platforms.forEach(function (p) {
            if (!p.is_system) {
                var selected = (selectedId && p.id == selectedId) ? 'selected' : '';
                html += '<option value="' + p.id + '" ' + selected + '>🎨 ' + p.name + '</option>';
            }
        });
        html += '</optgroup>';

        html += '<option value="add_new_platform" class="add-new-option">➕ <?= __('Add Platform') ?></option>';

        $(selectElement).html(html);
    }

    function addTask() {
        taskCount++;
        var template = $('#task-template').html();
        var html = template.replace(/{index}/g, taskCount).replace(/{step}/g, taskCount);
        $('#tasks-container').append(html);

        var newSelect = $('#tasks-container .task-item:last .platform-select');
        renderPlatformOptions(newSelect);
    }

    $(document).ready(function () {
        // Add initial task
        addTask();

        $('#add-task-btn').click(function () {
            addTask();
        });

        $(document).on('click', '.remove-task-btn', function () {
            $(this).closest('.task-item').remove();
            // Re-number steps
            var step = 1;
            $('.step-number').each(function () {
                $(this).text(step++);
            });
            taskCount--;
        });

        // Handle Platform Change
        $(document).on('change', '.platform-select', function () {
            var platformId = $(this).val();
            var actionSelect = $(this).closest('.task-item').find('.action-select');

            if (platformId === 'add_new_platform') {
                currentSelectElement = this;
                $('#addPlatformModal').modal('show');
                $(this).val(''); // Reset selection
                return;
            }

            if (platformId) {
                // Load Actions via AJAX
                $.ajax({
                    url: '<?= $this->Url->build(['controller' => 'PlatformActions', 'action' => 'getAvailableForPlatform']) ?>/' + platformId,
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            var html = '<option value=""><?= __('Select Action') ?></option>';

                            html += '<optgroup label="<?= __('System Actions') ?>">';
                            response.data.forEach(function (a) {
                                if (a.is_system) {
                                    html += '<option value="' + a.id + '">📌 ' + a.name + '</option>';
                                }
                            });
                            html += '</optgroup>';

                            html += '<optgroup label="<?= __('My Actions') ?>">';
                            response.data.forEach(function (a) {
                                if (!a.is_system) {
                                    html += '<option value="' + a.id + '">⚡ ' + a.name + '</option>';
                                }
                            });
                            html += '</optgroup>';

                            html += '<option value="add_new_action">➕ <?= __('Add Action') ?></option>';

                            actionSelect.html(html);
                        }
                    }
                });
            } else {
                actionSelect.html('<option value=""><?= __('Select Action') ?></option>');
            }
        });

        // Handle Action Change
        $(document).on('change', '.action-select', function () {
            var actionId = $(this).val();
            if (actionId === 'add_new_action') {
                var platformId = $(this).closest('.task-item').find('.platform-select').val();
                if (!platformId) {
                    alert('Please select a platform first');
                    $(this).val('');
                    return;
                }
                currentSelectElement = this;
                $('#new-action-platform-id').val(platformId);
                $('#addActionModal').modal('show');
                $(this).val('');
                return;
            }
        });

        // Save New Platform
        $('#save-platform-btn').click(function () {
            var name = $('#new-platform-name').val();
            if (!name) return;

            $.ajax({
                url: '<?= $this->Url->build(['controller' => 'Platforms', 'action' => 'add']) ?>',
                type: 'POST',
                data: { name: name },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        platforms.push(response.data); // Add to local list

                        // Refresh all platform dropdowns
                        $('.platform-select').each(function () {
                            var currentVal = $(this).val();
                            renderPlatformOptions(this, currentVal);
                        });

                        // Select new platform in current dropdown
                        if (currentSelectElement) {
                            renderPlatformOptions(currentSelectElement, response.data.id);
                            $(currentSelectElement).trigger('change'); // Trigger action load
                        }

                        $('#addPlatformModal').modal('hide');
                        $('#new-platform-name').val('');
                    }
                }
            });
        });

        // Save New Action
        $('#save-action-btn').click(function () {
            var name = $('#new-action-name').val();
            var platformId = $('#new-action-platform-id').val();
            if (!name || !platformId) return;

            $.ajax({
                url: '<?= $this->Url->build(['controller' => 'PlatformActions', 'action' => 'add']) ?>',
                type: 'POST',
                data: { name: name, platform_id: platformId },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        // Refresh action dropdown for current row
                        if (currentSelectElement) {
                            var platformSelect = $(currentSelectElement).closest('.task-item').find('.platform-select');
                            platformSelect.trigger('change'); // Reload actions

                            // Wait for AJAX to finish then select? 
                            // Better to manually append and select
                            // For simplicity, just triggering change will reload list, but we lose selection.
                            // Let's just append it for now.

                            // Actually, triggering change is safer to get full list. 
                            // We can use a timeout or promise to select the new one, but user can just select it.
                            // Let's try to auto select.
                            setTimeout(function () {
                                $(currentSelectElement).val(response.data.id);
                            }, 500);
                        }

                        $('#addActionModal').modal('hide');
                        $('#new-action-name').val('');
                    }
                }
            });
        });
    });
</script>
<?php $this->end(); ?>