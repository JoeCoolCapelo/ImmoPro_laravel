<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Document::class);
        $documents = Document::with(['bien', 'transaction', 'user'])->latest()->paginate(20);
        return view('documents.index', compact('documents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'type' => 'required|string',
            'bien_id' => 'nullable|exists:biens,id',
            'transaction_id' => 'nullable|exists:transactions,id',
        ]);

        $path = $request->file('file')->store('documents', 'public');

        Document::create([
            'titre' => $request->titre,
            'path' => $path,
            'type' => $request->type,
            'bien_id' => $request->bien_id,
            'transaction_id' => $request->transaction_id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Document ajouté avec succès.');
    }

    public function download(Document $document)
    {
        $this->authorize('view', $document);
        return Storage::disk('public')->download($document->path, $document->titre . '.' . pathinfo($document->path, PATHINFO_EXTENSION));
    }

    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);
        Storage::disk('public')->delete($document->path);
        $document->delete();

        return redirect()->back()->with('success', 'Document supprimé avec succès.');
    }
}
