<?php

namespace App\Models;

use Phalcon\Mvc\Collection;

class User extends Collection
{
    public function getSource()
    {
        return 'users';
    }

    public $hash;
    public $name;
    public $family;
    public $data;
    public $update;
    public $created_at;
    public $updated_at;

    public function beforeCreate()
    {
        $this->created_at = time();
        $this->updated_at = time();
    }

    public function beforeUpdate()
    {
        $this->updated_at = time();
    }

    public static function findByHash($hash)
    {
        return self::findFirst([
            [
                'hash' => (int)$hash
            ]
        ]);
    }

    public static function findWithPagination($page = 1, $limit = 10)
    {
        $skip = ($page - 1) * $limit;
        
        $users = self::find([
            'sort' => ['created_at' => -1],
            'skip' => $skip,
            'limit' => $limit
        ]);

        $total = self::count();

        return [
            'users' => $users,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'pages' => ceil($total / $limit)
        ];
    }
}