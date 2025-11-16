<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $userAgent = $request->header('User-Agent', '');
        $isMobile = preg_match('/(android|iphone|ipad|ipod|blackberry|windows phone|mobile)/i', $userAgent);

        $user = Auth::user();

        $filters = $request->only(['status', 'code', 'today']);
        $page = (int)($request->input('page', 1));
        $perPage = (int)($request->input('per_page', 15));

        // if (!$user->hasRole('admin')) {
        //     $filters['today'] = true;
        // }

        $products = $this->paginate($filters, $page, $perPage);

        $view = $isMobile ? 'Products/Mobile/Index' : 'Products/Index';

        return Inertia::render($view, [
            'products' => $products['data'],
            'total' => $products['total'],
            'filters' => $filters,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:products,code',
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
            'remarks' => 'nullable|string|max:500',
        ]);

        $product = Product::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Producto creado correctamente',
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:products,code,' . $id,
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
            'remarks' => 'nullable|string|max:500',
        ]);

        $product = Product::findOrFail($id);
        $product->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Producto actualizado correctamente',
            'data' => $product
        ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['success' => true, 'message' => 'Producto eliminado']);
    }

    public function listarProductos()
    {
        $productos = Product::all();

        return response()->json([
            'success' => true,
            'data' => $productos
        ]);
    }

    private function paginate(array $filters, int $page, int $perPage): array
    {
        $q = Product::query();

        if (!empty($filters['status'])) {
            $q->where('status', $filters['status']);
        }

        if (!empty($filters['code'])) {
            $q->where('code', 'like', '%' . $filters['code'] . '%');
        }

        if (!empty($filters['today'])) {
            $q->whereDate('created_at', now()->toDateString());
        }

        $total = (clone $q)->count();

        $rows = $q->orderByDesc('created_at')
            ->forPage($page, $perPage)
            ->get();

        return [
            'data' => $rows,
            'total' => $total,
        ];
    }
}
