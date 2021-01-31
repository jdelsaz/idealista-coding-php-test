<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\PictureRepository;
use App\Service\PicturesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;


final class PublicListingController extends AbstractController
{
    private int $scoreToShowAdvertising = 40;
    private AdRepository $adRepository;
    private PictureRepository $pictureRepository;

    public function __construct(AdRepository $adRepository, PictureRepository $pictureRepository)
    {
        $this->adRepository = $adRepository;
        $this->pictureRepository = $pictureRepository;
    }

    public function __invoke(): JsonResponse
    {
        $output = $this->getOutputAdvertising($this->adRepository->findAll(), true);

        return new JsonResponse([
            'success' => !empty($output),
            'data' => $output
        ]);
    }

    private function getOutputAdvertising($ads, $sort = true): array
    {
        $output = $arrayScoreValues = [];

        if(!empty($ads)){
            $calculateScoreController = new CalculateScoreController($this->adRepository, $this->pictureRepository);
            $picturesService = new PicturesService($this->pictureRepository);

            foreach($ads as $advertising){
                $score = $calculateScoreController->getScoreByAdvertisingId($advertising->getId());

                if($score >= $this->scoreToShowAdvertising){
                    $advertisingPictures = $advertising->getPictures();

                    $output[] = [
                        'id' => $advertising->getId(),
                        'typology' => $advertising->getTypology(),
                        'description' => $advertising->getDescription(),
                        'gardenSize' => $advertising->getGardenSize(),
                        'houseSize' => $advertising->getHouseSize(),
                        'pictureUrls' => !empty($advertisingPictures) ? $picturesService->getPictureUrls($advertisingPictures) : [],
                    ];

                    if($sort)
                    {
                        $arrayScoreValues[] = ['score' => $score];
                    }
                }
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
