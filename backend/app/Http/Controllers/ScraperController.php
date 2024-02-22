<?php

namespace App\Http\Controllers;

use App\Models\NewspaperModel;
use Illuminate\Http\Request;
use Goutte\Client;
use Illuminate\Support\Facades\Log;

class ScraperController extends Controller
{
    public function readAll()
    {
        $data = NewspaperModel::all();
        $client = new Client();
        $itemsD = array();
        $cont = 1;

        foreach ($data as $item) {
            try {
                $crawler = $client->request('GET', $item->link);
                $crawler->filter('article')->each(function ($node) use (&$itemsD, &$cont) {
                    $que = [
                        'id' => $cont,
                        'title' => $node->filter('h1, h2')->text(),
                        'link' => $node->filter('a')->attr('href'),
                    ];

                    $itemsD[] = $que;

                    $cont++;
                });
            } catch (\Throwable $th) {
                continue;
            }
        }

        return response()->json([
            'items' => $itemsD,
        ]);
    }

    public function readByName(Request $request)
    {
        $np = NewspaperModel::where('name', $request->name)->pluck('link');
        $client = new Client();
        $itemsD = array();
        $cont = 1;

        try {
            $crawler = $client->request('GET', $np);
            $crawler->filter('article')->each(function ($node) use (&$itemsD, &$cont) {
                $que = [
                    'id' => $cont,
                    'title' => $node->filter('h1, h2')->text(),
                    'link' => $node->filter('a')->attr('href'),
                ];

                $itemsD[] = $que;

                $cont++;
            });
        } catch (\Throwable $th) {
            ;
        }

        return response()->json([
            'items' => $itemsD,
            'np' => $np,
        ]);
    }

    public function add(Request $request)
    {
        $name = $request->name;
        $link = $request->link;

        $data = [
            'name' => $name,
            'link' => $link,
        ];

        $item = NewspaperModel::create($data);

        return response()->json([
            'item' => $item
        ]);
    }
}
