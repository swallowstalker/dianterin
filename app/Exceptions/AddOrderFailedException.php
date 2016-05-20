<?php
/**
 * Created by PhpStorm.
 * User: pulung
 * Date: 5/20/16
 * Time: 10:02 AM
 */

namespace App\Exceptions;


use Illuminate\Contracts\Support\MessageProvider;
use Illuminate\Contracts\Validation\ValidationException;

class AddOrderFailedException extends ValidationException
{
}