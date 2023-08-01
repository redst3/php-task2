<?php

declare(strict_types=1);
namespace Task2\Entity;

class Operation{
    const EUROPEAN_RATE = 0.01;
    const NON_EUROPEAN_RATE = 0.02;
    private string $bin;
    private float $amount;
    private string $currency;
    private bool $european = false;
    private float $exchangeRate = 1;
    public function __construct(string $bin, float $amount, string $currency)
    {
        $this->bin = $bin;
        $this->amount = $amount;
        $this->currency = $currency;
        
    }
    public function getCountryCodeByBin() : ?string 
    {
        $lookupResults = file_get_contents('https://lookup.binlist.net/' . $this->bin);
        if($lookupResults){
            $lookupResults = json_decode($lookupResults, true);
            if(isset($lookupResults['country']['alpha2'])){
                return $lookupResults['country']['alpha2'];
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
    public function setEuropean() : void
    {
        $this->european = true;
    }

    // using different api for exchange rates, because previous one needed api key, which I didn't have
    public function findExchangeRate(): float {
        $rateJson = json_decode(
            file_get_contents('https://developers.paysera.com/tasks/api/currency-exchange-rates')
        );

        if($this->currency !== 'EUR' || $rateJson !== null){
            foreach($rateJson->rates as $key => $value){
                if($key === $this->currency){
                    $this->exchangeRate = $value;
                    break;
                }
            }
        }
       return $this->calculateAndPrintFee();
    }
    private function calculateAndPrintFee(): float {
        $feeAmount = $this->amount / $this->exchangeRate;
        if($this->european){
            $fee = $feeAmount * self::EUROPEAN_RATE;
        } else {
            $fee = $feeAmount * self::NON_EUROPEAN_RATE;
        }
        $roundedFee = round($fee, 2);
        if($roundedFee < $fee){
            echo $roundedFee + 0.01 . "\n";
            return $roundedFee + 0.01;
        } else {
            echo $roundedFee . "\n";
            return $roundedFee;
        }
    }
}