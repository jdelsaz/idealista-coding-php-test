<?php

namespace App\Tests\Service;

use App\Repository\AdRepository;
use App\Service\ScoreCalculation;
use App\Repository\PictureRepository;
use App\Persistence\FileSystem\InFileSystemPersistence;
use PHPUnit\Framework\TestCase;

class ScoreCalculationTest extends TestCase
{
    public function testCalculate()
    {
        $inFileSystemPersistence = new InFileSystemPersistence();
        $pictureRepository = new PictureRepository($inFileSystemPersistence);
        $adRepository = new AdRepository($inFileSystemPersistence);
        $scoreCalculation = new ScoreCalculation($pictureRepository);

        //chalet
        $advertising = $adRepository->findById(3);
        $scoreCalculation->setAdvertising($advertising);
        $scoreCalculationResult = $scoreCalculation->calculate();

        $expectedResult = 20;
        $this->assertEquals($expectedResult, $scoreCalculationResult);

        //flat
        $advertising = $adRepository->findById(5);
        $scoreCalculation->setAdvertising($advertising);
        $expectedResult = 75;
        $scoreCalculationResult = $scoreCalculation->calculate();
        $this->assertEquals($expectedResult, $scoreCalculationResult);

        //garage
        $advertising = $adRepository->findById(7);
        $scoreCalculation->setAdvertising($advertising);
        $expectedResult = 0;
        $scoreCalculationResult = $scoreCalculation->calculate();
        $this->assertEquals($expectedResult, $scoreCalculationResult);

    }

}
