<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evaluation;

class EvaluatioController extends Controller
{
    public function showEvaluation()
    {
      $Evaluation=Evaluation::get();
     return response()->json($Evaluation, 200);
    
    }

}
