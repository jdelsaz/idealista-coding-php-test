<?php


namespace App\Service;


use App\Entity\Ad;
use App\Repository\PictureRepository;

class ScoreCalculation
{

    private Ad $advertising;
    private PictureRepository $pictureRepository;

    private int $minValue = 0;
    private int $maxValue = 100;

    public function __construct(PictureRepository $pictureRepository)
    {
        $this->pictureRepository = $pictureRepository;
    }

    public function setAdvertising(Ad $advertising)
    {
        $this->advertising = $advertising;
    }

    /**
     * @return int|null
     */
    public function calculate(): ?int
    {
        $pictureScore = $this->getPictureScore();
        $descriptionScore = $this->getDescriptionScore();
        $completeAdvertisingScore = $this->completeAdvertisingScore();

        return $this->getTotalScore($pictureScore, $descriptionScore, $completeAdvertisingScore);
    }

    private function getPictureScore(): int
    {
        $advertisingPictures = $this->advertising->getPictures();

        if(empty($advertisingPictures))
        {
            return -10;
        }

        $score = 0;
        foreach($advertisingPictures as $pictureId)
        {
            $quality = $this->pictureRepository->findById($pictureId)->getQuality();
            $score += $this->getPictureQualityScore($quality);;
        }

        return $score;
    }

    private function getPictureQualityScore($quality): int
    {
        switch (strtoupper($quality)){
            case 'HD':
                return 20;
            case 'SD':
                return 10;
        }

        return 0;
    }

    private function getDescriptionScore(): int
    {
        $description = $this->advertising->getDescription();
        $score = !empty($description) ? 5 : 0;

        if($score){
            $score += $this->getTypologyScore($description);
        }

        $score += $this->inWordScore($description);

        return $score;
    }

    private function getTypologyScore($description): int
    {
        $typology = strtolower($this->advertising->getTypology());
        $numberOfWords = str_word_count($description);

        switch ($typology){
            case 'chalet':
                return $numberOfWords > 50 ? 20 : 0;
            case 'flat':
                return $numberOfWords >= 20 && $numberOfWords <= 49 ? 10 : ($numberOfWords >= 50 ? 30 : 0);
        }

        return 0;
    }

    private function inWordScore($description): int
    {
        $words = ['Luminoso', 'Nuevo', 'Céntrico', 'Reformado', 'Ático'];

        $score = 0;
        foreach($words as $word){
            $numberOfWord = $this->substr_count_array(strtolower($description), strtolower($word));
            $score += $numberOfWord ? 5 : 0;
        }

        return $score;
    }

    private function completeAdvertisingScore(): int
    {
        $description = $this->advertising->getDescription();
        $typology = strtolower($this->advertising->getTypology());
        $isComplete = false;

        switch ($typology)
        {
            case 'chalet':
                $emptyDescription = empty($description);
                $emptyPicture = empty($this->advertising->getPictures());
                $emptyHouseSize = empty($this->advertising->getHouseSize());
                $emptyGardenSize = empty($this->advertising->getGardenSize());

                $isComplete = !$emptyDescription && !$emptyPicture && !$emptyHouseSize && !$emptyGardenSize;
                break;
            case 'flat':
                $emptyDescription = empty($description);
                $emptyPicture = empty($this->advertising->getPictures());
                $emptyHouseSize = empty($this->advertising->getHouseSize());

                $isComplete = !$emptyDescription && !$emptyPicture && !$emptyHouseSize;
                break;
            case 'garage':
                $emptyPicture = empty($this->advertising->getPictures());

                $isComplete = !$emptyPicture;
                break;
        }
        return $isComplete ? 40 : 0;
    }

    private function getTotalScore($pictureScore, $descriptionScore, $completeAdvertisingScore): int
    {
        $score = $pictureScore + $descriptionScore + $completeAdvertisingScore;
        return $score < $this->minValue ? $this->minValue : ($score > $this->maxValue ? $this->maxValue : $score);
    }

    private function substr_count_array($haystack, $needle): int
    {
        $bits_of_haystack = explode(' ', $haystack);
        return count(array_keys($bits_of_haystack, $needle));
    }
}