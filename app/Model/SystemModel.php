<?php
namespace App\Model;

class SystemModel extends BaseModel
{
    protected $table = 'system';

    public static function getAllSystem()
    {
        $systems = self::get();
        $ret = [];

        foreach($systems as $system) {
            $ret[$system->id] = $system->name;
        }

        return $ret;
    }

}
