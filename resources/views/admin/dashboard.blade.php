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
        <div class="col-lg-4 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{ $kategori }}</h3>

                    <p>Total Kategori</p>
                </div>
                <div class="icon">
                    <i class="fa fa-cube"></i>
                </div>
                <a href="{{ route('kategori.index') }}" class="small-box-footer">Lihat <i
                        class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ $produk }}</h3>

                    <p>Total Produk</p>
                </div>
                <div class="icon">
                    <i class="fa fa-cubes"></i>
                </div>
                <a href="{{ route('produk.index') }}" class="small-box-footer">Lihat <i
                        class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-4 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ $supplier }}</h3>

                    <p>Total Supplier</p>
                </div>
                <div class="icon">
                    <i class="fa fa-truck"></i>
                </div>
                <a href="{{ route('supplier.index') }}" class="small-box-footer">Lihat <i
                        class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <!-- /.row -->
    <!-- Main row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Grafik Pendapatan {{ tanggal_indonesia($tanggal_awal, false) }} s/d
                        {{ tanggal_indonesia($tanggal_akhir, false) }}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="chart">
                                <!-- Sales Chart Canvas -->
                                <canvas id="salesChart" style="height: 180px;"></canvas>
                            </div>
                            <!-- /.chart-responsive -->
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
            // Get context with jQuery - using jQuery's .get() method.
            var salesChartCanvas = $('#salesChart').get(0).getContext('2d');
            // This will get the first returned node in the jQuery collection.
            var salesChart = new Chart(salesChartCanvas);

            var salesChartData = {
                labels: {{ json_encode($data_tanggal) }},
                datasets: [{
                    label: 'Pendapatan',
                    fillColor: 'rgba(60,141,188,0.9)',
                    strokeColor: 'rgba(60,141,188,0.8)',
                    pointColor: '#3b8bba',
                    pointStrokeColor: 'rgba(60,141,188,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data: {{ json_encode($data_pendapatan) }}
                }]
            };

            var salesChartOptions = {
                pointDot: false,
                responsive: true
            };

            salesChart.Line(salesChartData, salesChartOptions);
        });
    </script>
@endpush
