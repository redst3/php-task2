<?php

declare(strict_types=1);
require('./vendor/autoload.php');

use Task2\Entity\Operation;

$europe_countries = 'src\Data\europe_countries.json';
if(isset($argv[1])){
    $file = $argv[1];
}else{
    $file = 'src\Data\input.txt';
}

if(file_exists($file)){
    if(file_exists($europe_countries)){
        $europeanCountries = json_decode(
            file_get_contents($europe_countries), 
            true
        );
    }else{
        echo 'European countries file does not exist in src\Data\europe_countries.json';
        exit;
    }

    $handle = fopen($file, 'r');
    $contents = fread($handle, filesize($file));
    fclose($handle);

    $operations = explode("\n", $contents);
    foreach($operations as $operation){
        $operationJson = json_decode(
            $operation, 
            true
        );
        $operation = new Operation(
            $operationJson['bin'],
            (float)$operationJson['amount'],
            $operationJson['currency']
        );
        $countryCode = $operation->getCountryCodeByBin();
        if($countryCode !== null){
            foreach($europeanCountries as $europeanCountry){
                if($europeanCountry['country_code'] === $countryCode){
                    $operation->setEuropean();
                    break;
                }
            }
        }
        $operation->findExchangeRate();
    }
}else {
    echo 'File does not exist.';
}