<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class ApiKey extends Entity
{
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

    protected $_hidden = [
        'api_secret'
    ];
}
