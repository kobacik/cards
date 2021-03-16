<?php

namespace App\Http\Controllers;

use App\Http\Requests\CardRequest;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $check = DB::table('requests')
            ->whereIpAddress(request()->ip())
            ->first();

        if(!$check) {
            DB::table('requests')->insert(
                [
                    'ip_address' => request()->ip(),
                    'request_count' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );

            Card::create([
                'title' => 'Backend Developer',
                'name' => 'Fatih KOBACIK',
                'phone' => '555 666 77 88',
                'email' => null,
                'ip_address' => request()->ip()
            ]);
            Card::create([
                'title' => 'Frontend Developer',
                'name' => 'Mehmet SERT',
                'phone' => '555 444 33 22',
                'email' => null,
                'ip_address' => request()->ip()
            ]);
        }else{
            DB::table('requests')
                ->whereIpAddress(request()->ip())
                ->update([
                    'request_count' => $check->request_count + 1,
                    'updated_at' => now()
                ]);
        }

        $cards = Card::whereIpAddress(request()->ip())
            ->orderBy('title')
            ->get();

        return response()->json($cards, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CardRequest $request)
    {
        $data = $request->validated();
        $data['ip_address'] = $request->ip();

        Card::create($data);

        return response()->json('Kartvizit oluşturma işlemi başarılı', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $card = Card::whereIpAddress(request()->ip())
            ->whereId($id)
            ->firstOrFail();

        return response()->json($card, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CardRequest $request, $id)
    {

        $card = Card::whereIpAddress($request->ip())
            ->whereId($id)
            ->firstOrFail();

        $card->update($request->validated());

        return response()->json('Kartvizit güncelleme işlemi başarılı.');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        Card::whereIpAddress($request->ip())
            ->whereId($id)
            ->delete();

        return response()->json('Kartvizit silme işlemi başarılı', 200);

    }
}
