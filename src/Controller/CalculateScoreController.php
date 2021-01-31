<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\PictureRepository;
use App\Service\ScoreCalculation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CalculateScoreController extends AbstractController
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
        $output = [];
        $ads = $this->adRepository->findAll();

        foreach($ads as $advertising){
            $output[] = [
                'id' => $advertising->getId(),
                'score' => $this->getScoreByAdvertisingId($advertising->getId()),
            ];
        }

        return new JsonResponse([
            'success' => !empty($output),
            'data' => $output
        ]);
    }

    public function getScoreByAdvertisingId($advertisingId): ?int
    {
        $advertising = $this->adRepository->findById($advertisingId);

        if(!empty($advertising))
        {
            $scoreCalculation = new ScoreCalculation($this->pictureRepository);
            $scoreCalculation->setAdvertising($advertising);
            return $scoreCalculation->calculate();
        }

        return null;
    }
}