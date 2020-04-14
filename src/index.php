<?php
include "estimator.php";

$input = array();
$input["population"] = $_POST["data-population"];
$input["timeToElapse"] = $_POST["data-time-to-elapse"];
$input["reportedCases"] = $_POST["data-reported-cases"];
$input["totalHospitalBeds"] = $_POST["data-total-hospital-beds"];
$input["periodType"] = (int)$_POST["data-period-type"];

$output = covid19ImpactEstimator($input);

