<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PriceRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'bien_id' => 'required|exists:biens,id',
            'new_price' => 'required|numeric|min:0',
            'reason' => 'nullable|string|max:500',
        ]);

        $bien = \App\Models\Bien::findOrFail($request->bien_id);
        $this->authorize('update', $bien);

        \App\Models\PriceRequest::create([
            'bien_id' => $bien->id,
            'old_price' => $bien->prix,
            'new_price' => $request->new_price,
            'reason' => $request->reason,
            'statut' => 'en_attente',
        ]);

        return redirect()->back()->with('success', 'Votre demande de modification de prix a été envoyée.');
    }
}
