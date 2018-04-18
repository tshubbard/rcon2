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
        $retItems = array();
        // get the server by ID and return it
        $items = Item::all();
        foreach ($items as &$item) {
            if (!isset($retItems[$item->itemType->id])) {
                $retItems[$item->itemType->id] = array(
                    "id" => $item->itemType->id,
                    "name" => $item->itemType->name,
                    "items" => array()
                );
            }

            $retItems[$item->itemType->id]["items"][] = $item;
        }

        return response()->json([
            'items' => $retItems,
        ]);
    }
}
