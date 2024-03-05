<?php

namespace App\Http\Controllers;

use App\Models\NewspaperModel;
use Illuminate\Http\Request;
use Goutte\Client;
use Illuminate\Support\Facades\DB;

class ScraperController extends Controller
{
    // mostrar todos los titulares
    public function index()
    {
        $data = NewspaperModel::all();
        $client = new Client();
        $itemsD = array();
        $textArray = array();
        $cont = 1;

        foreach ($data as $item) {
            try {
                $crawler = $client->request('GET', $item->link);
                $name = $item->name;
                $npId = NewspaperModel::where('name', $name)->value('id');
                $crawler->filter('article')->each(function ($node) use (&$itemsD, &$cont, $name, $npId, &$client, &$textArray) {
                    $que = [
                        'id' => $cont,
                        'title' => $node->filter('h1, h2')->text(),
                        'link' => $node->filter('a')->attr('href'),
                        'name' => $name,
                        'npId' => $npId,
                    ];

                    $text = '';
                    $crawler2 = $client->request('GET', $que['link']);
                    $crawler2->filter('article > .clearfix, .c-detail__body > p')->each(function ($node) use (&$text) {
                        $text .= $node->filter('p, .paragraph')->text();
                    });
                    $textArray[] = [$que['id'] => $text];

                    $itemsD[] = $que;

                    $cont++;
                });
            } catch (\Throwable $th) {
                continue;
            }
        }

        $titles = array();

        foreach ($itemsD as $item) {
            array_push($titles, $item['title']);
        }

        $check = array_diff($titles, $this->rss());

        return response()->json([
            'items' => $itemsD,
            'countTitles' => count($titles),
            'countCheck' => count($check),
            'check' => array_keys($check),
            'textArray' => $textArray
            /* 'rss' => $this->rss(),
            'check' => $check */
        ]);
    }

    // rss
    public function rss()
    {
        $client = new Client();
        $data = NewspaperModel::all();
        $itemsD = array();

        foreach ($data as $item) {
            try {
                $crawler = $client->request('GET', $item->rss);
                $crawler->filter('item')->each(function ($node) use (&$itemsD) {
                    $que = [
                        'title' => $node->filter('title')->text(),
                        // 'link' => $node->filter('a')->attr('href'),
                    ];

                    // $itemsD[] = $que;
                    array_push($itemsD, $que['title']);
                });
            } catch (\Throwable $th) {
                continue;
            }
        }

        return $itemsD;
    }

    // guardar un periódico
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'link' => 'required|url',
            'rss' => 'required|url',
        ]);

        $existingNewspaper = NewspaperModel::where('link', $request->link)->first();

        if ($existingNewspaper) {

            $npId = $existingNewspaper->id;
        } else {
            $newspaper = NewspaperModel::create([
                'name' => $request->name,
                'link' => $request->link,
                'rss' => $request->rss
            ]);

            $npId = $newspaper->id;
        }

        $userId = $request->userID;

        $data = [
            'user_id' => $userId,
            'newspaper_id' => $npId
        ];

        DB::table('user_newspaper')->insert($data);

        return response()->json([
            'message' => 'Periódico creado correctamente.',
            'newspaper' => $existingNewspaper ? $existingNewspaper : $newspaper,
            'data' => $data
        ], 201);
    }


    // actualizar un periódico
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:newspapers,name,' . $id,
            'link' => 'required|url',
        ]);

        $newspaper = NewspaperModel::findOrFail($id);

        $newspaper->update([
            'name' => $request->name,
            'link' => $request->link,
        ]);

        return response()->json([
            'message' => 'Periódico actualizado correctamente.',
            'newspaper' => $newspaper,
        ]);
    }

    // borrar un periódico
    public function destroy($id)
    {
        $newspaper = NewspaperModel::findOrFail($id);

        if ($newspaper) {
            $newspaper->delete();

            return response()->json([
                'message' => 'Periódico borrado correctamente.',
            ], 200);
        } else {
            return response()->json([
                'error' => 'Periódico no encontrado.',
            ], 404);
        }
    }

    // devolver los nombres de los periódicos guardados
    public function getNames()
    {
        $list = NewspaperModel::all()->pluck('name');

        return response()->json([
            'list' => $list,
        ]);
    }

    // leer noticias por nombre del periódico
    public function readByName(Request $request)
    {
        $client = new Client();
        $np = NewspaperModel::where('name', $request->name)->pluck('link')[0];
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
        ]);
    }

    // devolver los datos de un periódico
    public function getNpData(Request $request)
    {
        $np = NewspaperModel::where('name', $request->name)->get();

        return response()->json([
            'newspaper' => $np,
        ]);
    }

    public function userNewspapers(Request $request)
    {
        $nps = DB::table('user_newspaper')->where('user_id', $request->id)->pluck('newspaper_id');
        return response()->json([
            'nps' => $nps,
        ]);
    }
}
