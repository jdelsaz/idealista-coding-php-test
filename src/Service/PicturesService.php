<?php


namespace App\Service;

use App\Repository\PictureRepository;

class PicturesService
{

    private PictureRepository $pictureRepository;

    public function __construct(PictureRepository $pictureRepository)
    {
        $this->pictureRepository = $pictureRepository;
    }

    public function getPictureUrls($advertisingPictures): array
    {
        $picturesReturned = [];
        foreach($advertisingPictures as $pictureId)
        {
            $picturesReturned[] = $this->pictureRepository->findById($pictureId)->getUrl();
        }

        return $picturesReturned;
    }
}