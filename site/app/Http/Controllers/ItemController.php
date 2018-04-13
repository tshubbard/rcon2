<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;

class ItemController extends Controller
{
    /**
     * Gets all items
     *
     * GET /items
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // get the server by ID and return it
        $items = Item::all();
        foreach ($items as &$item) {
            $item->item_type_name = $item->itemType->name;
        }
        return response()->json([
            'items' => $items,
        ]);
    }
}
