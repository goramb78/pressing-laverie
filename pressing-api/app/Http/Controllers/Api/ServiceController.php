<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    // Catalogue : client voit seulement les actifs, gestionnaire voit tout, filtre par libellé possible
    public function index(Request $request)
    {
        $query = Service::query();

        if (! $request->user() || $request->user()->role !== 'gestionnaire') {
            $query->where('actif', true);
        }

        if ($request->filled('libelle')) {
            $query->where('libelle', 'like', '%'.$request->libelle.'%');
        }

        return response()->json($query->get());
    }

    public function show(Service $service)
    {
        return response()->json($service);
    }

    // Ajouter un service (gestionnaire uniquement, protégé par le middleware 'role' dans les routes)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix_unitaire' => 'required|numeric|min:0',
            'actif' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $service = Service::create($request->all());

        return response()->json($service, 201);
    }

    // Modifier un service
    public function update(Request $request, Service $service)
    {
        $validator = Validator::make($request->all(), [
            'libelle' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'prix_unitaire' => 'sometimes|required|numeric|min:0',
            'actif' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $service->update($request->all());

        return response()->json($service);
    }

    // Archiver (désactive, ne supprime pas physiquement)
    public function destroy(Service $service)
    {
        $service->update([
            'actif' => false,
            'archived_at' => now(),
        ]);

        return response()->json(['message' => 'Service archivé.']);
    }
}