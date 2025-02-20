<?php

namespace App\Http\Controllers;

use App\Models\Contato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContatoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Contato::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string',
            'telefone' => 'required|string',
            'endereco' => 'required|string',
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $contato = new Contato();
        $contato->nome = $request->nome;
        $contato->telefone = $request->telefone;
        $contato->endereco = $request->endereco;

        if ($request->hasFile('foto_perfil')) {
            $path = $request->file('foto_perfil')->store('fotos_perfil', 'public');
            $contato->foto_perfil = $path;
        }

        $contato->save();
        return response()->json($contato, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contato = Contato::find($id);
        if (!$contato) {
            return response()->json(['message' => 'Contato não encontrado'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $contato = Contato::find($id);

        if (!$contato) {
            return response()->json(['message' => 'Contato não encontrado'], 404);
        }

        $request->validate([
            'nome' => 'sometimes|string',
            'telefone' => 'sometimes|string',
            'endereco' => 'sometimes|string',
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->has('nome')) {
            $contato->nome = $request->nome;
        }

        if ($request->has('telefone')) {
            $contato->telefone = $request->telefone;
        }

        if ($request->has('endereco')) {
            $contato->endereco = $request->endereco;
        }

        if ($request->hasFile('foto_perfil')) {
            if ($contato->foto_perfil) {
                Storage::disk('public')->delete($contato->foto_perfil);
            }
            $path = $request->file('foto_perfil')->store('fotos_perfil', 'public');
            $contato->foto_perfil = $path;
        }

        $contato->save();
        return response()->json($contato);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contato = Contato::find($id);
        if (!$contato) {
            return response()->json(['message' => 'Contato não encontrado'], 404);
        }

        if ($contato->foto_perfil) {
            Storage::disk('public')->delete($contato->foto_perfil);
        }

        $contato->delete();
        return response()->json(['message' => 'Contato excluído com sucesso']);
    }
}
