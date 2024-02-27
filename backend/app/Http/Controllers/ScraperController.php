<?php

namespace App\Http\Controllers;

use App\Models\NewspaperModel;
use Illuminate\Http\Request;
use Goutte\Client;

class ScraperController extends Controller
{
    // mostrar todos los titulares
    public function index()
    {
        $data = NewspaperModel::all();
        $client = new Client();
        $itemsD = array();
        $cont = 1;

        foreach ($data as $item) {
            try {
                $crawler = $client->request('GET', $item->link);
                $name = $item->name;
                $npId = NewspaperModel::where('name', $name)->value('id');
                $crawler->filter('article')->each(function ($node) use (&$itemsD, &$cont, $name, $npId) {
                    $que = [
                        'id' => $cont,
                        'title' => $node->filter('h1, h2')->text(),
                        'link' => $node->filter('a')->attr('href'),
                        'name' => $name,
                        'npId' => $npId
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

    // guardar un periódico
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
}
