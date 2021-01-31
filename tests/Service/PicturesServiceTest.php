<?php


namespace App\Tests\Service;

use App\Service\PicturesService;
use App\Repository\PictureRepository;
use App\Persistence\FileSystem\InFileSystemPersistence;
use PHPUnit\Framework\TestCase;

class PicturesServiceTest extends TestCase
{

    public function testGetPictureUrls()
    {
        $inFileSystemPersistence = new InFileSystemPersistence();
        $pictureRepository = new PictureRepository($inFileSystemPersistence);
        $picturesService = new PicturesService($pictureRepository);

        $advertisingPictures = [3, 8];
        $expectedResult = ['https://www.idealista.com/pictures/3', 'https://www.idealista.com/pictures/8'];
        $picturesServiceResult = $picturesService->getPictureUrls($advertisingPictures);
        $this->assertEquals($expectedResult, $picturesServiceResult);

        $advertisingPictures = [2];
        $expectedResult = ['https://www.idealista.com/pictures/2'];
        $picturesServiceResult = $picturesService->getPictureUrls($advertisingPictures);
        $this->assertEquals($expectedResult, $picturesServiceResult);

    }
}