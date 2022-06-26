<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PredictionController extends Controller
{
    use PredictionTrait;

    public function getPredictions()
    {
        return $this->predictChampion();
    }
}
