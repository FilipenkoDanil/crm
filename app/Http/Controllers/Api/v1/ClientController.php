<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\ClientCreatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Client\StoreClientRequest;
use App\Http\Requests\v1\Client\UpdateClientRequest;
use App\Http\Resources\v1\ClientResource;
use App\Models\Client;
use App\Services\v1\ClientService;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    protected ClientService $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    public function index(): JsonResponse
    {
        return response()->json(ClientResource::collection(Client::all()));
    }

    public function store(StoreClientRequest $request): JsonResponse
    {
        broadcast(new ClientCreatedEvent())->toOthers();
        return response()->json(new ClientResource(Client::create($request->validated())));
    }

    public function show(Client $client): JsonResponse
    {
        return response()->json(new ClientResource($client));
    }

    public function update(UpdateClientRequest $request, Client $client): JsonResponse
    {
        return $this->clientService->update($request, $client);
    }

    public function destroy(Client $client): JsonResponse
    {
        return $this->clientService->delete($client);
    }
}
