<?php
namespace App\Enum;
enum ProductUnitEnum: int
{
    case SINGLE_ITEM = 1000;
    case BOX = 1002;
    case PACK = 1003;
    case CARTON = 1004;
    case BOTTLE = 1005;
}