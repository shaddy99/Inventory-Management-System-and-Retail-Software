<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
  public function index(Request $request)
  {
    $order_column = $request->get('orderBy');
    if (!in_array($order_column, ['id', 'name', 'email', 'phone', 'created_at'])) {
      $order_column = 'id';
    }
    $q =  '%' . $request->input('q') . '%';
    $suppliers = Supplier::where('name', 'LIKE', $q)
      ->orWhere('email', 'LIKE', $q)
      ->orWhere('phone', 'LIKE', $q)
      ->orderBy($order_column, $request->boolean('orderDesc') ? 'desc' : 'asc')
      ->paginate($request->get('per_page'));
    return SupplierResource::collection($suppliers);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function search(Request $request)
  {
    $q =  '%' . $request->input('q') . '%';
    $items = Supplier::select(['id', 'name'])
      ->where('name', 'LIKE', $q)
      ->orWhere('email', 'LIKE', $q)
      ->orWhere('phone', 'LIKE', $q)
      ->get();
    if ($items->IsEmpty()) {
      $items = [[
        'id'    => 0,
        'name'  => $request->input('q')
      ]];
    }

    return response()->json($items);
  }

  public function store(SupplierRequest $request)
  {
    $supplier = new Supplier($request->validated());
    $supplier->save();
    return response()->json([
      'status'  => 'success',
      'message' => 'Supplier record added sucessfully.',
      'data'    => ['Supplier_id' => $supplier->id]
    ]);
  }

  public function show($id)
  {
    $supplier = Supplier::find($id);
    return response()->json($Supplier);
  }

  public function update($id, SupplierRequest $request)
  {
    $supplier = Supplier::findOrFail($id);
    $supplier->update($request->all());
    return response()->json([
      'status'  => 'success',
      'message' => 'Supplier record updated sucessfully.',
      'data'    => ['Supplier_id' => $supplier->id]
    ]);
  }

  public function destroy($id)
  {
    $supplier = Supplier::find($id);
    $supplier->delete();
    return response()->json([
      'message' => "Supplier $supplier->name deleted successfully."
    ]);
  }
}


// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\Supplier;

// class SupplierController extends Controller
// {
//     public function getSuppliers(){
//         $suppliers = Supplier::all();
//         return $suppliers;
//     }

//     public function saveSuppliers(Request $request){
//         $supplier = new Supplier;

//         $supplier = $request->name;
//         $supplier = $request->email;
//         $supplier = $request->phone;
//         $supplier = $request->gender;
//         $supplier = $request->address;
//         $supplier = $request->city;
//         $supplier = $request->province;
//         $supplier = $request->comments;

//         if($supplier->save()){
//             return response()->json(['status' => true, 'message' => 'Contact Added Successfully']);
//             return response()->json(['status'=> false, 'message' => 'There is some problem']);
//         }
//     }
// } 
