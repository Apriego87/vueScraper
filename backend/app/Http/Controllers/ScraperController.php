<?php

namespace App\Http\Controllers;

use App\Models\NewspaperModel;
use Illuminate\Http\Request;
use Goutte\Client;

class ScraperController extends Controller
{
    public function read()
    {
        $client = new Client();
        $url = 'https://www.elmundo.es/';
        $crawler = $client->request('GET', $url);
        $items = $crawler->filter('article')->each(function ($node, $i) {
            // echo $node->filter('a')->text();
            return [
                'id' => $i + 1,
                'title' => $node->filter('h1, h2')->text(),
                'link' => $node->filter('a')->attr('href'),
            ];
            // echo $node->filter('h2')->text() . '<br>';

        });

        // var_dump($crawler->filter('h2'));
        return $items;
        // return view('index', ['items' => $items]);
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
