<?php

namespace App\Http\Controllers\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class UpdateProductController extends Controller
{
    private $product;

    public function __construct() {
        $this->product = new Product();
    }

    public function update(Request $request, string $id)
    {

        try{
            $request->validate([
                'name' => 'string|max:100',
                'price' => 'regex:/^\d+(\.\d{1,2})$/',
                'barcode' => 'string|max:50|unique:products,barcode',
            ], [
                'name.max' => 'Nome muito longo.',
                'price.regex' => 'Preço deve ser um número decimal válido.',
                'barcode.max' => 'Código de barras muito longo.',
                'barcode.unique' => Rule::unique('products', 'barcode')->ignore($id)
            ]);
        }catch(\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->errors()], Response::HTTP_BAD_REQUEST);
        }

        try{
            $data = $request->all();
            $this->product->updateData($id, $data);

            return response()->json(['message' => 'Produto atualizado com sucesso.'], Response::HTTP_OK);
        }catch(\Exception $e) {
            return response()->json(['error' => 'Erro ao criar produto: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
