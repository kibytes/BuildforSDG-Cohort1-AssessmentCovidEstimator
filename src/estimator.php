<?php

function covid19ImpactEstimator($data)
{
    $d_estimator = new Estimator(json_decode($data));
    $e_impact = new Impact($d_estimator);
    $e_severeImpact = new SevereImpact($d_estimator);
    $output = (object)[];
    $output->data = $d_estimator;
    $output->impact = $e_impact;
    $output->severeImpact = $e_severeImpact;
    $data = json_encode($output);
    return $data;
}

class Estimator
{
    var $region;
    var $periodType;
    var $timeToElapse;
    var $reportedCases;
    var $population;
    var $totalHospitalBeds;

    function __construct($JSONObject)
    {
        $this->region = new Region($JSONObject->region);
        $this->periodType = $JSONObject->periodType;
        $this->timeToElapse = $JSONObject->timeToElapse;
        $this->reportedCases = $JSONObject->reportedCases;
        $this->population = $JSONObject->population;
        $this->totalHospitalBeds = $JSONObject->totalHospitalBeds;
    }
}

class Region
{
    var $name;
    var $avgAge;
    var $avgDailyIncomeInUSD;
    var $avgDailyIncomePopulation;

    function __construct($obj)
    {
        $this->name = $obj->name;
        $this->avgAge = $obj->avgAge;
        $this->avgDailyIncomeInUSD = $obj->avgDailyIncomeInUSD;
        $this->avgDailyIncomePopulation = $obj->avgDailyIncomePopulation;
    }
}

class Impact
{
    var $currentlyInfected;
    var $infectionsByRequestedTime;

    function __construct($data)
    {
        $days = normalizePeriod($data->timeToElapse, $data->periodType);
        $this->currentlyInfected = $data->reportedCases * 10;
        $this->infectionsByRequestedTime = $this->currentlyInfected * (2 ** floor($days / 3));
    }
}

class SevereImpact
{
    var $currentlyInfected;
    var $infectionsByRequestedTime;

    function __construct($data)
    {
        $days = normalizePeriod($data->timeToElapse, $data->periodType);
        $this->currentlyInfected = $data->reportedCases * 50;
        $this->infectionsByRequestedTime = $this->currentlyInfected * (2 ** floor($days / 3));
    }
}

function normalizePeriod($time, $periodType)
{
    $days = $time;
    if ($periodType == "months")
    {
        $days = $time * 30;
    }
    if ($periodType == "weeks")
    {
        $days = $time * 7;
    }
    return $days;
}


//$myRegion = (object)[];
//
//$myRegion->name = "Africa";
//$myRegion->avgAge = 19.7;
//$myRegion->avgDailyIncomeInUSD = 5;
//$myRegion->avgDailyIncomePopulation = 0.71;
//
//$obj = (object)[];
//
//$obj->region = $myRegion;
//$obj->periodType = "days";
//$obj->timeToElapse = 58;
//$obj->reportedCases = 674;
//$obj->population = 66622705;
//$obj->totalHospitalBeds = 1380614;
//
//echo (covid19ImpactEstimator(json_encode($obj)));

//echo("</br>");
//echo(6740 * 2 ** floor(58/3));

//echo $obj->name;