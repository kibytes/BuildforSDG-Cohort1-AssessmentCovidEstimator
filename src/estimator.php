<?php

function covid19ImpactEstimator($data)
{
    $d_estimator = new Estimator($data);
    $e_impact = new Impact($d_estimator);
    $e_severeImpact = new SevereImpact($d_estimator);
    $output = array("data"=>$d_estimator->getData(),
        "impact"=>$e_impact->getData(),
        "severeImpact"=>$e_severeImpact->getData());

    $data = $output;
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
        $this->region = new Region($JSONObject["region"]);
        $this->periodType = $JSONObject["periodType"];
        $this->timeToElapse = $JSONObject["timeToElapse"];
        $this->reportedCases = $JSONObject["reportedCases"];
        $this->population = $JSONObject["population"];
        $this->totalHospitalBeds = $JSONObject["totalHospitalBeds"];
    }

    function getData()
    {
        return array("region"=>$this->region->getData(),
            "periodType"=>$this->periodType,
            "timeToElapse"=>$this->timeToElapse,
            "reportedCases"=>$this->reportedCases,
            "population"=>$this->population,
            "totalHospitalBeds"=>$this->totalHospitalBeds);
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
        $this->name = $obj["name"];
        $this->avgAge = $obj["avgAge"];
        $this->avgDailyIncomeInUSD = $obj["avgDailyIncomeInUSD"];
        $this->avgDailyIncomePopulation = $obj["avgDailyIncomePopulation"];
    }

    function getData()
    {
        return array("name"=>$this->name, "avgAge"=>$this->avgAge, "avgDailyIncomeInUSD"=>$this->avgDailyIncomeInUSD,
            "avgDailyIncomePopulation"=>$this->avgDailyIncomePopulation);
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

    function getData()
    {
        return array("currentlyInfected"=>$this->currentlyInfected,
            "infectionsByRequestedTime"=>$this->infectionsByRequestedTime);
    }
}

class SevereImpact
{
    var $currentlyInfected;
    var $infectionsByRequestedTime;
    var $severeCasesByRequestedTime;
    var $hospitalBedsByRequestedTime;

    function __construct($data)
    {
        $days = normalizePeriod($data->timeToElapse, $data->periodType);
        $this->currentlyInfected = $data->reportedCases * 50;
        $this->infectionsByRequestedTime = $this->currentlyInfected * (2 ** floor($days / 3));
        $this->severeCasesByRequestedTime = floor(.15 * $this->infectionsByRequestedTime);
        $this->hospitalBedsByRequestedTime = .35 * $data->totalHospitalBeds - $this->severeCasesByRequestedTime;
    }

    function getData()
    {
        return array("currentlyInfected"=>$this->currentlyInfected,
            "infectionsByRequestedTime"=>$this->infectionsByRequestedTime,
            "severeCasesByRequestedTime"=>$this->severeCasesByRequestedTime,
            "hospitalBedsByRequestedTime"=>$this->hospitalBedsByRequestedTime);
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