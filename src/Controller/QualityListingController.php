<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\PictureRepository;
use App\Service\PicturesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

final class QualityListingController extends AbstractController
{
    private AdRepository $adRepository;
    private PictureRepository $pictureRepository;

    public function __construct(AdRepository $adRepository, PictureRepository $pictureRepository)
    {
        $this->adRepository = $adRepository;
        $this->pictureRepository = $pictureRepository;
    }

    public function __invoke(): JsonResponse
    {
        $output = $this->getOutputAdvertising($this->adRepository->findAll());

        return new JsonResponse([
            'success' => !empty($output),
            'data' => $output
        ]);
    }

    private function getOutputAdvertising($ads, $sort = false): array
    {
        $output = $arrayScoreValues = [];

        $calculateScoreController = new CalculateScoreController($this->adRepository, $this->pictureRepository);
        $picturesService = new PicturesService($this->pictureRepository);

        foreach ($ads as $advertising) {
            $score = $calculateScoreController->getScoreByAdvertisingId($advertising->getId());
            $advertisingPictures = $advertising->getPictures();

            $output[] = [
                'id' => $advertising->getId(),
                'typology' => $advertising->getTypology(),
                'description' => $advertising->getDescription(),
                'gardenSize' => $advertising->getGardenSize(),
                'houseSize' => $advertising->getHouseSize(),
                'score' => $score,
                'pictureUrls' => !empty($advertisingPictures) ? $picturesService->getPictureUrls($advertisingPictures) : [],
                'irrelevantSince' => $advertising->getIrrelevantSince(),
            ];

            if($sort)
            {
                $arrayScoreValues[] = ['score' => $score];
            }
        }

        if($sort)
        {
            $sortScores  = array_column($arrayScoreValues, 'score');
            array_multisort($sortScores, SORT_DESC, $output);
        }

        return $output;
    }
}