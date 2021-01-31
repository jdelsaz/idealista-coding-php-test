<?php

namespace App\Repository;

use App\Persistence\FileSystem\InFileSystemPersistence;

class AdRepository
{
    protected $inFileSystemPersistence;

    public function __construct(InFileSystemPersistence $inFileSystemPersistence)
    {
        $this->inFileSystemPersistence = $inFileSystemPersistence;
    }

    public function findAll()
    {
        return $this->inFileSystemPersistence->getAds();
    }

    public function findById($id)
    {
        $ads = $this->findAll();

        foreach($ads as $advertising){
            if($advertising->getId() == $id)
            {
                return $advertising;
            }
        }

        return [];
    }
}