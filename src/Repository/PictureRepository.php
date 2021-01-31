<?php

namespace App\Repository;

use App\Persistence\FileSystem\InFileSystemPersistence;

class PictureRepository
{
    protected $inFileSystemPersistence;

    public function __construct(InFileSystemPersistence $inFileSystemPersistence)
    {
        $this->inFileSystemPersistence = $inFileSystemPersistence;
    }

    public function findAll()
    {
        return $this->inFileSystemPersistence->getPictures();
    }

    public function findById($pictureId)
    {
        $allPictures = $this->findAll();

        foreach($allPictures as $picture)
        {
            if($picture->getId() == $pictureId)
            {
                return $picture;
            }
        }

        return [];
    }
}