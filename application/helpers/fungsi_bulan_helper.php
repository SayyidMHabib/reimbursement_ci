<?php
function getBulan($bln){
    switch ($bln){
        case 1: 
            return "JAN";
            break;
        case 2:
            return "FEB";
            break;
        case 3:
            return "MAR";
            break;
        case 4:
            return "APR";
            break;
        case 5:
            return "MEI";
            break;
        case 6:
            return "JUN";
            break;
        case 7:
            return "JUL";
            break;
        case 8:
            return "AGU";
            break;
        case 9:
            return "SEP";
            break;
        case 10:
            return "OKT";
            break;
        case 11:
            return "NOV";
            break;
        case 12:
            return "DES";
            break;
    }
}
?>