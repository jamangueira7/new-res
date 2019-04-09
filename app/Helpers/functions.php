<?php
/**
 * Created by PhpStorm.
 * User: João Mangueira
 * Date: 04/04/2019
 * Time: 11:16
 */

function getColor($num) {
    $color[0] = '#00A0E3';
    $color[1] = '#009846';
    $color[2] = '#D19A9D';
    $color[3] = '#CB5499';
    $color[4] = '#E5097F';
    $color[5] = '#AE4A84';
    $color[6] = '#EF7F1A';
    $color[7] = '#F5B2B6';
    $color[8] = '#86776F';
    $color[9] = '#D2CDE7';
    $color[10] = '#7690C9';
    $color[11] = '#7E71B1';
    $color[12] = '#9089B0';
    $color[13] = '#56698F';
    $color[14] = '#5E5971';
    $color[15] = '#7688A1';
    $color[16] = '#008DD2';
    $color[17] = '#A8D4AF';
    $color[18] = '#B0CB1F';
    $color[19] = '#DCCF73';
    $color[20] = '#F08143';
    $color[21] = '#FECC00';

    return !empty($color[$num]) ? $color[$num] : '#'.rand ( 0 , 9).rand ( 0 , 9).rand ( 0 , 9).rand ( 0 , 9).rand ( 0 , 9).rand ( 0 , 9);
}//getColor

function dataSimple($data) {
    $new_data = explode('/', $data);

    $new_data = $new_data[2] . '-' . $new_data[1] . '-' . $new_data[0];
    return $new_data;
}//dataSimple


function convDateMySQLforDateTime($data, $ini = true) {
    $new_data = explode('/', $data);

    $new_data = $new_data[2] . '-' . $new_data[1] . '-' . $new_data[0];
    $new_data .= ($ini) ? " 00:00:01" : " 23:59:59";
    return $new_data;
}//convDateMySQLforDateTime

function convDateTimeWithBr($data) {
    $new_data = explode('-', $data);
    $hour = explode(' ', $new_data[2]);

    $new_data = $hour[0] . '/' . $new_data[1] . '/' . $new_data[0] . ' ' . $hour[1];
    return $new_data;
}//convDateTimeWithBr

function convDateWithBr($data) {
$new_data = explode('-', $data);

$new_data = $new_data[0] . '/' . monthTextForNumber($new_data[1]) . '/' . $new_data[2];
return $new_data;
}//convDateWithBr

function monthTextForNumber($data) {
    switch ($data) {
        case 'JAN':
            return '01';
            break;
        case 'FEV':
            return '02';
            break;
        case 'MAR':
            return '03';
            break;
        case 'ABR':
            return '04';
            break;
        case 'MAI':
            return '05';
            break;
        case 'JUN':
            return '06';
            break;
        case 'JUL':
            return '07';
            break;
        case 'AGO':
            return '08';
            break;
        case 'SET':
            return '09';
            break;
        case 'OUT':
            return '10';
            break;
        case 'NOV':
            return '11';
            break;
        case 'DEZ':
            return '12';
            break;

    }

}//convDateWithBr

function formaterUnimedCodes($data) {
    $resp = "";
    foreach ($data as $d){
        if(!empty($resp)){
            $resp.= ",";
        }
        $resp.= "'" . $d . "'";
    }
    return $resp;
}//formaterUnimedCodes

function formaterUnimedCodesUnique($data) {
    $resp = "";
    foreach ($data as $d){
        if(!empty($resp)){
            $resp.= ",";
        }
        $resp.= "'" . explode("-",$d)[0] . "'";
    }
    return $resp;
}//formaterUnimedCodesUnique

function formaterServicesCodes($data) {
    $resp = "";
    foreach ($data as $d){
        if(!empty($resp)){
            $resp.= ",";
        }
        $resp.= "'" . explode("-",$d)[0] . "'";
    }
    return $resp;
}//formaterUnimedCodes
