<?php

namespace App\Services\v1;

use App\Events\ClientDeletedEvent;
use App\Http\Requests\v1\Client\UpdateClientRequest;
use App\Http\Resources\v1\ClientResource;
use App\Models\Client;
use Illuminate\Http\JsonResponse;

class ClientService
{
    public function update(UpdateClientRequest $request, Client $client): JsonResponse
    {
        if ($client->id == 1) {
            return response()->json(['success' => false, 'message' => 'This client cannot be updated.'], 422);
        }

        $client->update($request->validated());
        return response()->json(new ClientResource($client));
    }

    public function delete(Client $client): JsonResponse
    {
        if ($client->id == 1) {
            return response()->json(['success' => false, 'message' => 'This client cannot be deleted.'], '422');
        }

        $client->delete();
        broadcast(new ClientDeletedEvent())->toOthers();
        return response()->json(['success' => true, 'message' => 'Client deleted.']);
    }
}
