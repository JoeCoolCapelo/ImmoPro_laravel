<?php

namespace App\Http\Controllers;

use App\Models\Bien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Auth::user()->favorites()->with('images')->latest()->paginate(12);
        return view('favorites.index', compact('favorites'));
    }

    public function toggle(Bien $bien)
    {
        $user = Auth::user();
        
        if ($user->favorites()->where('bien_id', $bien->id)->exists()) {
            $user->favorites()->detach($bien->id);
            $message = 'Retiré des favoris.';
            $status = 'removed';
        } else {
            $user->favorites()->attach($bien->id);
            $message = 'Ajouté aux favoris.';
            $status = 'added';
        }

        if (request()->ajax()) {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'count' => $user->favorites()->count()
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
}
