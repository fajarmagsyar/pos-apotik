<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index()
    {

        $kategori = Satuan::all();
        return view('satuan.index', compact('kategori'));
    }

    public function data()
    {
        $satuan = Satuan::get();

        return datatables()
            ->of($satuan)
            ->addIndexColumn()
            ->addColumn('select_all', function ($satuan) {
                return '
                    <input type="checkbox" name="id_satuan[]" value="' . $satuan->id_satuan . '">
                ';
            })
            ->addColumn('nama_satuan', function ($satuan) {
                return $satuan->nama_satuan;
            })
            ->addColumn('aksi', function ($satuan) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`' . route('satuan.update', $satuan->id_satuan) . '`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`' . route('satuan.destroy', $satuan->id_satuan) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'select_all'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $satuan = new Satuan();
        $satuan->nama_satuan = $request->nama_satuan;
        $satuan->save();

        return response()->json('Data berhasil disimpan', 200);
    }
    public function show($id)
    {
        $satuan = Satuan::find($id);

        return response()->json($satuan);
    }
    public function update(Request $request, $id)
    {
        $satuan = Satuan::find($id);
        $satuan->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }
    public function destroy($id)
    {
        $satuan = Satuan::find($id);
        $satuan->delete();

        return response(null, 204);
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->id_satuan as $id) {
            $satuan = Satuan::find($id);
            $satuan->delete();
        }

        return response(null, 204);
    }
}
