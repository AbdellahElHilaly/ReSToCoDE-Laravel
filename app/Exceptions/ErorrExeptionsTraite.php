<?php
namespace App\Exceptions;

use App\Exceptions\Base\DeveMoodExeption;
use App\Traits\ExceptionHandling\BuildeMoodExeption;

    Trait ErorrExeptionsTraite{
        // use BuildeMoodExeption;
        use DeveMoodExeption;

        private function handleException(\Exception $e){
            return parent::handleException($e);
        }
    }
?>
