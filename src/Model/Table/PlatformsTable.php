<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class PlatformsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('platforms');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);

        $this->hasMany('PlatformActions', [
            'foreignKey' => 'platform_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        return $validator;
    }

    public function findAvailableForUser($query, array $options)
    {
        $userId = $options['user_id'];
        return $query->where([
            'OR' => [
                ['Platforms.is_system' => 1],
                ['Platforms.user_id' => $userId]
            ],
            'Platforms.is_active' => 1
        ]);
    }
}
