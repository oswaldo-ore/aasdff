<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buyer;
use App\Http\Controllers\ApiController;

class BuyerController extends ApiController
{
    public function index()
    {
        $compradores = Buyer::has('transactions')->get();
        return $this->showAll($compradores);
    }


    public function show(Buyer $buyer)
    {
        return $this->showOne($buyer);
    }
}
