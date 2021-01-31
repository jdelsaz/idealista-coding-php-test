<?php

namespace App\Tests\Controller;

use App\Repository\AdRepository;
use App\Repository\PictureRepository;
use App\Persistence\FileSystem\InFileSystemPersistence;
use App\Controller\CalculateScoreController;
use PHPUnit\Framework\TestCase;

class CalculateScoreControllerTest extends TestCase
{

    public function testInvoke()
    {
        $inFileSystemPersistence = new InFileSystemPersistence();
        $pictureRepository = new PictureRepository($inFileSystemPersistence);
        $adRepository = new AdRepository($inFileSystemPersistence);
        $calculateScoreController = new CalculateScoreController($adRepository, $pictureRepository);
        $calculateScoreControllerInvokeResult = $calculateScoreController->__invoke()->getContent();

        $expectedJson = '{"success":true,"data":[{"id":1,"score":0},{"id":2,"score":75},{"id":3,"score":20},{"id":4,"score":75},{"id":5,"score":75},{"id":6,"score":50},{"id":7,"score":0},{"id":8,"score":25}]}';
        $this->assertJsonStringEqualsJsonString($expectedJson, $calculateScoreControllerInvokeResult);
    }

}
