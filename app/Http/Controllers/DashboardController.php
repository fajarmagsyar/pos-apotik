<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Member;
use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $kategori = Kategori::count();
        $produk = Produk::count();
        $supplier = Supplier::count();
        $member = Member::count();

        $tanggal_awal = date('Y-m-01');
        $tanggal_akhir = date('Y-m-d');

        $data_tanggal = array();
        $data_pendapatan = array();

        while (strtotime($tanggal_awal) <= strtotime($tanggal_akhir)) {
            $data_tanggal[] = (int) substr($tanggal_awal, 8, 2);

            $total_penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('bayar');
            $total_pembelian = Pembelian::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('bayar');
            $total_pengeluaran = Pengeluaran::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('nominal');

            $pendapatan = $total_penjualan - $total_pembelian - $total_pengeluaran;
            $data_pendapatan[] += $pendapatan;

            $tanggal_awal = date('Y-m-d', strtotime("+1 day", strtotime($tanggal_awal)));
        }

        $tanggal_awal = date('Y-m-01');

        if (auth()->user()->level == 1) {
            return view('admin.dashboard', compact('kategori', 'produk', 'supplier', 'member', 'tanggal_awal', 'tanggal_akhir', 'data_tanggal', 'data_pendapatan'));
        } else {
            return view('kasir.dashboard');
        }
    }
    public function dataRestok()
    {
        $restok = Produk::whereRaw('stok-stokminimum < 1')
            ->select('nama_produk', 'stok', 'stokminimum')
            ->get();


        return datatables()
            ->of($restok)
            ->addIndexColumn()
            ->addColumn('selisih', function ($restok) {
                return '
                    <span class="badge text-white" style="background-color: red; color: white">' . $restok->stok - $restok->stokminimum . '</span>
                ';
            })
            ->rawColumns(['selisih'])
            ->make(true);
    }
    public function dataExpired()
    {
        $expired = Produk::leftJoin('supplier', 'supplier.id_supplier', '=', 'produk.id_supplier')
            ->select('produk.nama_produk', 'produk.stok', 'produk.stokminimum', 'supplier.nama AS nama_supplier', 'produk.expired_date')
            ->whereRaw("produk.expired_date IS NOT NULL AND DATE_SUB(expired_date, INTERVAL 8 MONTH) <= '" . date('Y-m-d') . "'")
            ->get();

        return datatables()
            ->of($expired)
            ->addIndexColumn()
            ->addColumn('expired_date', function ($exp) {
                return '
                <div class="mb-1"><i class="fa fa-calendar"></i> ' . $exp->expired_date . '</div>
                <span class="label label-danger">' . Carbon::now()->subMonth(1)->diffInMonths($exp->expired_date) . ' Bulan lagi</span>
                ';
            })
            ->rawColumns(['expired_date'])
            ->make(true);
    }
}
