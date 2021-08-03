<?php


namespace App\Services;


use App\Lead;
use Illuminate\Support\Str;

class RandomCodeGenerator
{

    /**
     * generate random code
     * @param $quantity
     * @param $length
     * @return string
     */
    public static function randomCode($quantity, $length)
    {
        for($i = 0; $i < $quantity; $i++){
            $randomCode = Str::random($length);
        }
        /** @var string $randomCode */
        return $randomCode;
    }

    public static function runRandomCode()
    {
        $code = RandomCodeGenerator::randomCode(5,10);
        if(Lead::where('extra_attribute->facebook_page_code',$code)->count() === 0)
        {
            return $code;
        }
        return RandomCodeGenerator::runRandomCode();
    }
}

