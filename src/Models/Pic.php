<?php

namespace Models;

use Models\Model;
use Models\ModelFinder;

class Pic extends Model
{
    const   tableName = "pic";
    
    private $overwrite;

    function __construct($db, Array $attributes)
    {
        parent::__construct($db, self::tableName);

        $mf  = new ModelFinder($db);
        $ids = $mf->findModels(self::tableName, $attributes);

        $attr = $attributes;

        if(count($ids) > 1)
        {
            throw new \Exception("Doublon found in database (adresse_mac = " . $this->adresseMAC . ")");
        }
        else if(count($ids) == 1)
        {
            $this->overwrite = true;
            $this->load($ids[0]);
        }
        else
        {
            $attr['id'] = 0;
            $this->overwrite = false;
        }

        $this->setAttributes($attr);
    }

    function toArray()
    {
        return $this->getAttributes();
    }

    function toJson()
    {
        return json_encode($this->toArray(), true);
    }

    static function getAllPics($db, $id_trip)
    {
        $pics = [];
        $mf   = new ModelFinder($db);
        $ids  = $mf->findModels(self::tableName, ['id_trip' => $id_trip]);

        foreach ($ids as $id) 
        {
            $pics[] = new Pic($db, ['id' => $id]);
        }

        return $pics;
    }
}

?>