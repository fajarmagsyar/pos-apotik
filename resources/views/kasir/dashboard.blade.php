@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Dashboard</li>
@endsection

@section('content')
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-body text-center">
                    <h1>Selamat Datang</h1>
                    <h2>Anda login sebagai KASIR</h2>
                    <br><br>
                    <a href="{{ route('transaksi.baru') }}" class="btn btn-success btn-lg">Transaksi Baru</a>
                    <br><br><br>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Data Obet <span class="badge bg-secondary">PERLU RE-STOK</span> dan Perlu
                        dilakukan <span class="badge bg-warning">Cek
                            Expiry Date</span>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <h4 class="text-center"><strong>Perlu RE-STOK</strong></h4>
                            <hr>
                            <div class="box-body table-responsive">
                                <table class="table table-restok table-stiped table-bordered">
                                    <thead>
                                        <th width="5%">No</th>
                                        <th>Nama Obat</th>
                                        <th>Stok</th>
                                        <th>Stok Minimum</th>
                                        <th>Selisih</th>
                                    </thead>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <h4 class="text-center"><strong>Perlu Cek Tanggal Expired</strong></h4>
                            <hr>
                            <div class="box-body table-responsive">
                                <table class="table-expired table-stiped table-bordered">
                                    <thead>
                                        <th width="5%">No</th>
                                        <th>Nama Obat</th>
                                        <th>Stok</th>
                                        <th>Supplier</th>
                                        <th>Expiry Date</th>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row (main row) -->
    <!-- /.row (main row) -->
@endsection

@push('scripts')
    <!-- ChartJS -->
    <script src="{{ asset('AdminLTE-2/bower_components/chart.js/Chart.js') }}"></script>
    <script>
        let tableRestok;
        let tableExpired;
        $(function() {
            tableRestok = $('.table-restok').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('dashboard.dataRestok') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'nama_produk'
                    },
                    {
                        data: 'stok'
                    },
                    {
                        data: 'stokminimum'
                    },
                    {
                        data: 'selisih'
                    }
                ]
            });
            tableExpired = $('.table-expired').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('dashboard.dateExpired') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'nama_produk'
                    },
                    {
                        data: 'stok'
                    },
                    {
                        data: 'nama_supplier'
                    },
                    {
                        data: 'expired_date'
                    }
                ]
            });
        });
    </script>
@endpush
