<?php

namespace App\Http\Controllers;

use App\Models\NewspaperModel;
use Illuminate\Http\Request;
use Goutte\Client;
use Illuminate\Support\Facades\Log;

class ScraperController extends Controller
{
    public function index()
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
        $np = NewspaperModel::where('name', $request->name)->pluck('link')[0];
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
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:newspapers',
            'link' => 'required|url',
        ]);
        $newspaper = NewspaperModel::create([
            'name' => $request->name,
            'link' => $request->link,
        ]);

        return response()->json([
            'message' => 'Periódico creado correctamente.',
            'newspaper' => $newspaper,
        ], 201);
    }

    public function destroy(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:newspapers,name,' . $id,
            'link' => 'required|url',
        ]);

        $newspaper = NewspaperModel::findOrFail($id);

        if ($newspaper) {
            $newspaper->delete();

            return response()->json([
                'message' => 'Periódico borrado correctamente.',
            ]);
        } else {
            return response()->json([
                'error' => 'Periódico no encontrado.',
            ], 404);
        }
    }

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
}
