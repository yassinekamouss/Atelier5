<?php 

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Events\StockUpdated;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        return response()->json(Stock::all());
    }

    public function store(Request $request)
    {
        $stock = Stock::create($request->validate([
            'product_name' => 'required|string',
            'quantity' => 'required|integer'
        ]));

        broadcast(new StockUpdated($stock))->toOthers();

        return response()->json($stock);
    }

    public function update(Request $request, Stock $stock)
    {
        $stock->update($request->validate([
            'quantity' => 'required|integer'
        ]));

        broadcast(new StockUpdated($stock))->toOthers();

        return response()->json($stock);
    }

    public function destroy(Stock $stock)
    {
        $stock->delete();

        broadcast(new StockUpdated($stock))->toOthers();

        return response()->json(['message' => 'Stock deleted']);
    }
}
