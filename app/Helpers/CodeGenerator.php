<?php
namespace App\Helpers;

class CodeGenerator
{

    public static function generateCode(): string
    {
        return date("Ymd") . substr(md5(uniqid(mt_rand(), true)), 0, 6);
    }
}

