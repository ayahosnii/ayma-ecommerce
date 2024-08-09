<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Listening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ListeningController extends Controller
{
    public function index()
    {
        $listenings = Listening::paginate(10);
        return response()->json($listenings, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'audio' => 'required|file|mimes:audio/mpeg,mpga,mp3,wav',
            'transcript' => 'nullable|string',
        ]);

        $audioPath = $request->file('audio')->store('audio_files', 'public');

        $listening = Listening::create([
            'title' => $request->title,
            'audio_url' => $audioPath,
            'transcript' => $request->transcript,
        ]);

        return response()->json($listening, 201);
    }

    public function show($id)
    {
        $listening = Listening::findOrFail($id);
        return response()->json($listening, 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'audio' => 'nullable|file|mimes:audio/mpeg,mpga,mp3,wav',
            'transcript' => 'nullable|string',
        ]);

        $listening = Listening::findOrFail($id);

        if ($request->hasFile('audio')) {
            Storage::disk('public')->delete($listening->audio_url);

            $audioPath = $request->file('audio')->store('audio_files', 'public');
            $listening->audio_url = $audioPath;
        }

        $listening->title = $request->title;
        $listening->transcript = $request->transcript;
        $listening->save();

        return response()->json($listening, 200);
    }

    public function destroy($id)
    {
        $listening = Listening::findOrFail($id);

        Storage::disk('public')->delete($listening->audio_url);

        $listening->delete();

        return response()->json(['message' => 'Listening deleted successfully'], 200);
    }

    public function countListening()
    {
        $countListening = Listening::count();
        return response()->json(['countListening' => $countListening], 200);
    }
}
