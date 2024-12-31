<?php
namespace App\Enum;
enum StockUnitEnum: string
{
    case WHOLESALE = 'WHL';
    case RETAIL = 'RTL';
    case BOX = 'BX';
    case KILOS = 'KG';
}