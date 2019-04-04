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
}
