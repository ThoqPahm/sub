<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class PlatformAction extends Entity
{
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
