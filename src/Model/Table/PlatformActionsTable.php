<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class PlatformActionsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('platform_actions');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Platforms', [
            'foreignKey' => 'platform_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        return $validator;
    }

    public function findAvailableForPlatform($query, array $options)
    {
        $platformId = $options['platform_id'];
        $userId = $options['user_id'];

        return $query->where([
            'PlatformActions.platform_id' => $platformId,
            'OR' => [
                ['PlatformActions.is_system' => 1],
                ['PlatformActions.user_id' => $userId]
            ],
            'PlatformActions.is_active' => 1
        ]);
    }
}
