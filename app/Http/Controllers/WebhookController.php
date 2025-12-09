<?php

namespace App\Http\Controllers;

use App\Models\Webhook;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function index()
    {
        $webhooks = Webhook::all();
        return view('webhooks.index', compact('webhooks'));
    }

    public function create()
    {
        return view('webhooks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'events' => 'required|array',
            'secret' => 'nullable|string',
        ]);

        Webhook::create([
            'name' => $request->name,
            'url' => $request->url,
            'events' => $request->events,
            'secret' => $request->secret,
            'is_active' => true,
        ]);

        return redirect()->route('superadmin.webhooks.index')->with('success', 'Webhook creado exitosamente.');
    }

    public function edit(Webhook $webhook)
    {
        return view('webhooks.edit', compact('webhook'));
    }

    public function update(Request $request, Webhook $webhook)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'events' => 'required|array',
            'secret' => 'nullable|string',
        ]);

        $webhook->update([
            'name' => $request->name,
            'url' => $request->url,
            'events' => $request->events,
            'secret' => $request->secret,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('superadmin.webhooks.index')->with('success', 'Webhook actualizado exitosamente.');
    }

    public function destroy(Webhook $webhook)
    {
        $webhook->delete();
        return redirect()->route('superadmin.webhooks.index')->with('success', 'Webhook eliminado exitosamente.');
    }
}
