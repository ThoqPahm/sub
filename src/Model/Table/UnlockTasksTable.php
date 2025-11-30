<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class UnlockTasksTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('unlock_tasks');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Links', [
            'foreignKey' => 'link_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Platforms', [
            'foreignKey' => 'platform_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('PlatformActions', [
            'foreignKey' => 'platform_action_id',
            'joinType' => 'INNER',
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('platform_url')
            ->requirePresence('platform_url', 'create')
            ->notEmptyString('platform_url');

        return $validator;
    }
}
