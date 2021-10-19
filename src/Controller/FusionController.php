<?php

namespace App\Controller;

use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\Writer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Service\FusionClass;

class FusionController extends AbstractController
{

    /**
     * @Route ("/fusion", name= "fusion")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/FusionController.php',
        ]);
    }



//Number,Gender,NameSet,Title,GivenName,MiddleInitial,Surname,StreetAddress,City,State,StateFull,ZipCode,Country,CountryFull,EmailAddress,Username,Password,BrowserUserAgent,TelephoneNumber,TelephoneCountryCode,MothersMaiden,Birthday,TropicalZodiac,CCType,CCNumber,CVV2,CCExpires,NationalID,UPS,WesternUnionMTCN,MoneyGramMTCN,Color,Occupation,Company,Vehicle,Domain,BloodType,Pounds,Kilograms,FeetInches,Centimeters,Latitude,Longitude
    /**
     * @Route ("/readcsv", name="readcsv")
     * @throws Exception
     */
    public function read(FusionClass $fusion )
    {
        //$source="/home/laupa/Vidéos/Vetux-Line/Vetux/Vetux-Line/csvFile/";
        $file='../var/uploads/file1.csv'  ;
        $csv = Reader::createFromPath($file, 'r');
        $csv->setHeaderOffset(0);
        $header = $csv->getHeader(); //returns the CSV header record
        $records = $csv->getRecords(); //returns all the CSV records as an Iterator object
        $output = Writer::createFromPath('../public/csv/output.csv');
        $tabName = ["Gender", "GivenName","Surname","Birthday","StreetAddress","Title","EmailAddress","TelephoneNumber","Kilograms","CCType","CCNumber","CVV2","CCExpires","Vehicle"];
        //  $tabName = ["Gender", "GivenName","Surname","Birthday","StreetAddress","EmailAddress","Centimeters","FeetInches"];
        $output->insertOne($tabName);//0,


        $fusion->fusion($records,$output,$tabName);





        return $this->render('/fusion/read.html.twig', array(
            'records' => $records,'header'=>$header
        ));
    }




    /**
     * @Route ("/sel")
     */
    public function selesct()
    {
        $source="/home/laupa/Vidéos/Vetux-Line/Vetux/Vetux-Line/csvFile/";
        $csv = Reader::createFromPath($source . 'french-data.csv', 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords(); //returns all the CSV records as an Iterator object


        return $this->render('/fusion/read.html.twig', array(
            'records' => $records,
        ));
    }


    /**
     * @Route("/download",name="download")
     */
    public function download(): BinaryFileResponse
    {
        $response = new BinaryFileResponse('../public/csv/output.csv');
        $response->headers->set('Content-Type', 'text/csv');
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'fusion.csv'
        );
        return $response;
    }
}

