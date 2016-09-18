<?php

namespace Models;

use Models\Model;
use Models\ModelFinder;

class Trip extends Model
{
    const   tableName = "trip";
    
    private $overwrite;

    function __construct($db, $id)
    {
        parent::__construct($db, self::tableName);

        $mf  = new ModelFinder($db);
        $ids = $mf->findModels(self::tableName, array('id' => $id));

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
            $this->overwrite = false;
        }
    }

    function setName($name)
    {
        $this->setAttribute('name', $name);

        return $this->overwrite;
    }

    function getName()
    {
        return $this->getAttribute('name');
    }

    static function getAllTrips($db)
    {
        $trips = [];
        $mf = new ModelFinder($db);
        $ids = $mf->findAllModels(self::tableName);

        foreach ($ids as $id) 
        {
            $trips[] = (new Trip($db, $id))->getAttributes();
        }

        return $trips;
    }
}

?>