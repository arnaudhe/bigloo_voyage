<?php

namespace Models;

use Models\Model;
use Models\ModelFinder;
use Models\Pic;

class Trip extends Model
{
    const   tableName = "trip";
    
    private $overwrite;

    private $pics;

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
            $this->pics = Pic::getAllPics($db, $ids[0]);
        }
        else
        {
            $attr['id'] = 0;
            $this->pics = [];
            $this->overwrite = false;
        }

        $this->setAttributes($attr);
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

    function getPicNb()
    {
        return count($this->pics);
    }

    function getPic($index)
    {
        $out = [];

        if (($index >= 0) && ($index < count($this->pics)))
        {
            $out['current'] = $this->pics[$index]->toArray();
            if ($index > 0)
            {
                $out['previous'] = $this->pics[$index - 1]->toArray();
            }
            if ($index < (count($this->pics) - 1))
            {
                $out['next'] = $this->pics[$index + 1]->toArray();
            }
        }

        return $out;
    }

    function getPicById($id)
    {
        foreach ($this->pics as $pic) 
        {
            if ($pic->getAttribute('id') == $id)
            {
                return $pic;
            }
        }
        return 0;
    }

    function addPic($path, $description)
    {
        $pic = New Pic($this->getDb(), ['id_trip' => $this->getAttribute('id'), 'path' => $path, 'description' => $description]);
        $this->pics = Pic::getAllPics($this->getDb(), $this->getAttribute('id'));
    }

    function toArray()
    {
        $a = $this->getAttributes();
        
        $a['pics'] = [];
        foreach ($this->pics as $pic) 
        {
            $a['pics'][] = $pic->toArray();
        }

        return $a;
    }

    function toJson()
    {
        return json_encode($this->toArray(), true);
    }

    static function getAllTrips($db)
    {
        $trips = [];
        $mf = new ModelFinder($db);
        $ids = $mf->findAllModels(self::tableName);

        foreach ($ids as $id) 
        {
            $trips[] = new Trip($db, ['id' => $id]);
        }

        return $trips;
    }
}

?>