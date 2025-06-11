<?php

namespace App\Http\Controllers\Client;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeleteClientController extends Controller
{
    private $client;

    public function __construct() {
        $this->client = new Client();
    }

    public function delete(Request $request, string $id)
    {
        try {
            $this->client->deleteData($id);
            return response()->json(['message' => 'Cliente deletado com sucesso.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao deletar cliente: ' . $e->getMessage()], 500);
        }
    }
}
