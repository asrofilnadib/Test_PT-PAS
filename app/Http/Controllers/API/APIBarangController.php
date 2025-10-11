<?php

  namespace App\Http\Controllers\API;

  use App\Http\Controllers\BarangController;
  use App\Http\Controllers\Controller;
  use App\Models\Barang;
  use Illuminate\Http\Request;

  class APIBarangController extends Controller
  {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
      //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
      $model = new Barang();
      $data = $model->getDetailBarang($id);

      if (empty($data)) {
        return response()->json([
          'status' => 'error',
          'message' => 'No data found for the given value.',
          'data' => []
        ]);
      }

      return response()->json([
        'status' => 'success',
        'message' => 'Data found',
        'data' => $data
      ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      //
    }
  }
