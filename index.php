<?php
require_once "functions.php";

verifyUser();

$action = $_GET["action"] ?? 'showHistories';
$tableName = $_GET["tableName"] ?? 'users';
$seedingPage = $_GET["seedingPage"] ?? 1;
$country = $_GET["country"] ?? "all";
$username = $_GET["username"] ?? "";
$dateFrom = $_GET["dateFrom"] ?? date('Y-m-01');
$dateTo = $_GET["dateTo"] ?? date('Y-m-31');



switch ($action){
    case 'clearRecords':
        clearRecords($tableName);exit;
    case 'seedUsers':
        seedUsers(3000);exit;
    case 'seedHistories':
        if($seedingPage > 30){
            exit;
        }

        seedHistories(100000, 3000);
        
        $seedingPage ++;
        header("Location: http://localhost/technical-test/index.php?action={$action}&seedingPage={$seedingPage}", true, 301);exit;

    case 'showHistories':
        $datetimeFrom = $dateFrom.' 00:00:00';
        $datetimeTo = $dateTo.' 23:59:59';

        include "layouts/historiesLayout.php";exit;;
    case 'showHistoriesByCountryAndDate':
        $datetimeFrom = $dateFrom.' 00:00:00';
        $datetimeTo = $dateTo.' 23:59:59';
        $country =  $country == 'all' ? 'Malaysia' : $country;
        $username = $username.'%';
    
        include "layouts/historiesByCountryAndDateLayout.php";exit;
    default:

        echo "Invalid Parameters.";exit;
}