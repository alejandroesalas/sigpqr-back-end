<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use App\Traits\ValitadorTrait;

class ApiController extends Controller
{
    use ApiResponser;
    use ValitadorTrait;
}
