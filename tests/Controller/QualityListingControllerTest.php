<?php

namespace App\Tests\Controller;

use App\Repository\AdRepository;
use App\Repository\PictureRepository;
use App\Persistence\FileSystem\InFileSystemPersistence;
use App\Controller\QualityListingController;
use PHPUnit\Framework\TestCase;

class QualityListingControllerTest extends TestCase
{

    public function testInvoke()
    {
        $inFileSystemPersistence = new InFileSystemPersistence();
        $pictureRepository = new PictureRepository($inFileSystemPersistence);
        $adRepository = new AdRepository($inFileSystemPersistence);
        $qualityListingController = new QualityListingController($adRepository, $pictureRepository);
        $qualityListingControllerInvokeResult = $qualityListingController->__invoke()->getContent();

        $expectedJson = '{"success":true,"data":[{"id":1,"typology":"CHALET","description":"Este piso es una ganga, compra, compra, COMPRA!!!!!","gardenSize":null,"houseSize":300,"score":0,"pictureUrls":[],"irrelevantSince":null},{"id":2,"typology":"FLAT","description":"Nuevo \u00e1tico c\u00e9ntrico reci\u00e9n reformado. No deje pasar la oportunidad y adquiera este \u00e1tico de lujo","gardenSize":null,"houseSize":300,"score":75,"pictureUrls":["https:\/\/www.idealista.com\/pictures\/4"],"irrelevantSince":null},{"id":3,"typology":"CHALET","description":"","gardenSize":null,"houseSize":300,"score":20,"pictureUrls":["https:\/\/www.idealista.com\/pictures\/2"],"irrelevantSince":null},{"id":4,"typology":"FLAT","description":"\u00c1tico c\u00e9ntrico muy luminoso y reci\u00e9n reformado, parece nuevo","gardenSize":null,"houseSize":300,"score":75,"pictureUrls":["https:\/\/www.idealista.com\/pictures\/5"],"irrelevantSince":null},{"id":5,"typology":"FLAT","description":"Pisazo,","gardenSize":null,"houseSize":300,"score":75,"pictureUrls":["https:\/\/www.idealista.com\/pictures\/3","https:\/\/www.idealista.com\/pictures\/8"],"irrelevantSince":null},{"id":6,"typology":"GARAGE","description":"","gardenSize":null,"houseSize":300,"score":50,"pictureUrls":["https:\/\/www.idealista.com\/pictures\/6"],"irrelevantSince":null},{"id":7,"typology":"GARAGE","description":"Garaje en el centro de Albacete","gardenSize":null,"houseSize":300,"score":0,"pictureUrls":[],"irrelevantSince":null},{"id":8,"typology":"CHALET","description":"Maravilloso chalet situado en lAs afueras de un peque\u00f1o pueblo rural. El entorno es espectacular, las vistas magn\u00edficas. \u00a1C\u00f3mprelo ahora!","gardenSize":null,"houseSize":300,"score":25,"pictureUrls":["https:\/\/www.idealista.com\/pictures\/1","https:\/\/www.idealista.com\/pictures\/7"],"irrelevantSince":null}]}';
        $this->assertJsonStringEqualsJsonString($expectedJson, $qualityListingControllerInvokeResult);

        $notExpectedJson = '{"success":true,"data":[{"id":2,"typology":"FLAT","description":"Nuevo \u00e1tico c\u00e9ntrico reci\u00e9n reformado. No deje pasar la oportunidad y adquiera este \u00e1tico de lujo","gardenSize":null,"houseSize":300,"pictureUrls":["https:\/\/www.idealista.com\/pictures\/4"]},{"id":4,"typology":"FLAT","description":"\u00c1tico c\u00e9ntrico muy luminoso y reci\u00e9n reformado, parece nuevo","gardenSize":null,"houseSize":300,"pictureUrls":["https:\/\/www.idealista.com\/pictures\/5"]},{"id":5,"typology":"FLAT","description":"Pisazo,","gardenSize":null,"houseSize":300,"pictureUrls":["https:\/\/www.idealista.com\/pictures\/3","https:\/\/www.idealista.com\/pictures\/8"]},{"id":6,"typology":"GARAGE","description":"","gardenSize":null,"houseSize":300,"pictureUrls":["https:\/\/www.idealista.com\/pictures\/6"]},{"id":8,"typology":"CHALET","description":"Maravilloso chalet situado en lAs afueras de un peque\u00f1o pueblo rural. El entorno es espectacular, las vistas magn\u00edficas. \u00a1C\u00f3mprelo ahora!","gardenSize":null,"houseSize":300,"pictureUrls":["https:\/\/www.idealista.com\/pictures\/1","https:\/\/www.idealista.com\/pictures\/7"]},{"id":3,"typology":"CHALET","description":"","gardenSize":null,"houseSize":300,"pictureUrls":["https:\/\/www.idealista.com\/pictures\/2"]},{"id":1,"typology":"CHALET","description":"Este piso es una ganga, compra, compra, COMPRA!!!!!","gardenSize":null,"houseSize":300,"pictureUrls":[]},{"id":7,"typology":"GARAGE","description":"Garaje en el centro de Albacete","gardenSize":null,"houseSize":300,"pictureUrls":[]}]}';
        $this->assertJsonStringNotEqualsJsonString($notExpectedJson, $qualityListingControllerInvokeResult);

        $notExpectedJson = '{"success":true,"data":[{"id":2,"typology":"FLAT","description":"Nuevo \u00e1tico c\u00e9ntrico reci\u00e9n reformado. No deje pasar la oportunidad y adquiera este \u00e1tico de lujo","gardenSize":null,"houseSize":300,"score":75,"pictureUrls":["https:\/\/www.idealista.com\/pictures\/4"],"irrelevantSince":null},{"id":4,"typology":"FLAT","description":"\u00c1tico c\u00e9ntrico muy luminoso y reci\u00e9n reformado, parece nuevo","gardenSize":null,"houseSize":300,"score":75,"pictureUrls":["https:\/\/www.idealista.com\/pictures\/5"],"irrelevantSince":null},{"id":5,"typology":"FLAT","description":"Pisazo,","gardenSize":null,"houseSize":300,"score":75,"pictureUrls":["https:\/\/www.idealista.com\/pictures\/3","https:\/\/www.idealista.com\/pictures\/8"],"irrelevantSince":null},{"id":6,"typology":"GARAGE","description":"","gardenSize":null,"houseSize":300,"score":50,"pictureUrls":["https:\/\/www.idealista.com\/pictures\/6"],"irrelevantSince":null},{"id":8,"typology":"CHALET","description":"Maravilloso chalet situado en lAs afueras de un peque\u00f1o pueblo rural. El entorno es espectacular, las vistas magn\u00edficas. \u00a1C\u00f3mprelo ahora!","gardenSize":null,"houseSize":300,"score":25,"pictureUrls":["https:\/\/www.idealista.com\/pictures\/1","https:\/\/www.idealista.com\/pictures\/7"],"irrelevantSince":null},{"id":3,"typology":"CHALET","description":"","gardenSize":null,"houseSize":300,"score":20,"pictureUrls":["https:\/\/www.idealista.com\/pictures\/2"],"irrelevantSince":null},{"id":1,"typology":"CHALET","description":"Este piso es una ganga, compra, compra, COMPRA!!!!!","gardenSize":null,"houseSize":300,"score":0,"pictureUrls":[],"irrelevantSince":null},{"id":7,"typology":"GARAGE","description":"Garaje en el centro de Albacete","gardenSize":null,"houseSize":300,"score":0,"pictureUrls":[],"irrelevantSince":null}]}';
        $this->assertJsonStringNotEqualsJsonString($notExpectedJson, $qualityListingControllerInvokeResult);
    }

}