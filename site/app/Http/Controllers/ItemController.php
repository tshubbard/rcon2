<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Config;

class ItemController extends Controller
{
    /**
     * Gets all items
     *
     * GET /items
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $requestItemsHash = intval($request->input('items_hash'));
        $dbItemsHash = Config::where('name' , '=', 'items_hash')->first();
        $dbItemsHash = intval($dbItemsHash->value);

        if ($requestItemsHash == $dbItemsHash) {
            return response()->json([
                'current' => true
            ]);
        } else {
            $retItems = array();
            // get the server by ID and return it
            $items = Item::all();
            foreach ($items as &$item) {
                if (!isset($retItems[$item->item_type_id])) {
                    $retItems[$item->item_type_id] = array(
                        'id' => $item->item_type_id,
                        'name' => $item->itemType->name,
                        'items' => array()
                    );
                }

                $retItems[$item->item_type_id]['items'][] = $item;
            }

            return response()->json([
                'current' => false,
                'rustItemsTS' => $dbItemsHash,
                'items' => $retItems
            ]);
        }
        
    }
}
