<?php

namespace App\Http\Controllers;

use App\Models\Visite;
use App\Models\Transaction;
use Illuminate\Http\Request;

class CRMController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Transaction::class);

        $leads = Visite::where('statut', 'en_attente')->with(['bien', 'client'])->latest()->get();
        $confirmed = Visite::where('statut', 'confirmée')->with(['bien', 'client'])->latest()->get();
        $negotiations = Visite::where('statut', 'effectuée')->where('interested', true)->with(['bien', 'client'])->latest()->get();
        $won = Transaction::with(['bien', 'client'])->latest()->take(10)->get();

        return view('admin.crm.index', compact('leads', 'confirmed', 'negotiations', 'won'));
    }
}
