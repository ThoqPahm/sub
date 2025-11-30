<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class UnlockLogsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('unlock_logs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Links', [
            'foreignKey' => 'link_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('UnlockTasks', [
            'foreignKey' => 'unlock_task_id',
            'joinType' => 'INNER',
        ]);
    }
}
