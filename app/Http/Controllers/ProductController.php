<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function __construct()
    {
    }

    public function list()
    {
        return [
            'status'    => true,
            'data'      => Product::all()
        ];
    }

    public function create(Request $request)
    {
        $rules = [
            'sku'       => 'required|string|unique:products',
            'name'      => 'required|string',
            'qty'       => 'required|numeric|min:0',
            'price'     => 'required|numeric|min:0',
            'unit'      => 'required|string',
            'status'    => 'boolean'
        ];

        $this->validate($request, $rules);

        try {
            if (Product::create($request->all())) {
                return [
                    'status'    => true,
                    'message'   => 'Succesfully creating product.'
                ];
            }

            return [
                'status'    => false,
                'message'   => 'Failed on creating product.'
            ];
        } catch (Exception $err) {
            return [
                'status'    => false,
                'message'   => 'Failed on creating product.'
            ];
        }
    }

    public function get(Request $request, string $sku)
    {
        try {
            $product = Product::where(['sku' => $sku])->first();

            if (!$product) {
                return [
                    'status'    => false,
                    'message'   => sprintf('No product found with SKU %s.', $sku)
                ];
            }

            return [
                'status'    => true,
                'data'      => Product::where(['sku' => $sku])->first()
            ];
        } catch (Exception $err) {
            Log::error(sprintf('Error on getting product by sku [%s].', $err->getMessage()));
            return [
                'status'    => false,
                'message'   => 'Failed on retrieving product'
            ];
        }
    }

    public function update(Request $request, string $sku)
    {
        $rules = [
            'sku'       => 'string|unique:products',
            'name'      => 'string',
            'qty'       => 'numeric|min:0',
            'price'     => 'numeric|min:0',
            'unit'      => 'string',
            'status'    => 'boolean'
        ];

        $this->validate($request, $rules);

        try {
            $product = Product::where(['sku' => $sku])->update($request->all());
            
            if (!$product) {
                return [
                    'status' => false,
                    'message' => 'Failed on updating product.'
                ];
            }

            return [
                'status' => true,
                'message' => 'Successfully updating product.'
            ];
        } catch (Exception $err) {
            Log::error(sprintf('Error on updating product with sku [%s].', $err->getMessage()));
            return [
                'status' => false,
                'message' => 'Failed on updating product.'
            ];
        }
    }

    public function delete(string $sku)
    {
        try {
            $product = Product::where(['sku' => $sku])->first();

            if (!$product) {
                return [
                    'status' => false,
                    'message' => sprintf('No product found with SKU %s.', $sku)
                ];
            }

            if (!$product->delete()) {
                return [
                    'status' => false,
                    'message' => sprintf('Failed on deleting product.')
                ];
            }

            return [
                'status' => true,
                'message' => 'Successfully deleting product.'
            ];
        } catch (Exception $err) {
            Log::error(sprintf('Error on deleting product with sku [%s].', $err->getMessage()));
            return [
                'status' => false,
                'message' => 'Failed on deleting product.'
            ];
        }
    }
}
