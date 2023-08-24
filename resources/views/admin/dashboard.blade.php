@extends('layouts.main')
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        @include('layouts.session')
        <h1 class="h2">Dashboard</h1>
    </div>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">Total Pengguna</div>
                <h3 class="card-body">{{$user}}</h3>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{route('users.page')}}">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">Total Kursus</div>
                <h3 class="card-body">{{$course}}</h3>

                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{route('course.page')}}">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">Total Transaksi</div>
                <h3 class="card-body">{{$transaction}}</h3>

                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{route('transactions.page')}}">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">Total Laporan</div>
                <h3 class="card-body">{{$report}}</h3>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{route('report.page')}}">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i>
                    Chart Pengguna (Perkembangan Bulanan)
                </div>
                <div class="card-body">
                    <canvas id="myLineChart" width="100%" height="100%"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Chart Transaksi (Perkembangan Bulanan)
                </div>
                <div class="card-body">
                    <canvas id="myLineTrxChart" width="100%" height="100%"></canvas>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-history me-1"></i>
                        Aktivitas Pengguna (Terbaru)
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($activityLog as $log)
                                <li class="list-group-item p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h6 class="card-title">{{ $log->description }}</h6>
                                            <small class="text-muted">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $log->created_at)->format('d F Y, H:i:s') }}</small>
                                        </div>
                                        <div class="flex-shrink-0">
                                            @if($log->causer_type === 'App\Models\User')
                                                <i class="fas fa-user-circle fa-2x text-primary"></i>
                                            @elseif($log->causer_type === 'App\Models\Course')
                                                <i class="fas fa-book fa-2x text-success"></i>
                                            @elseif($log->causer_type === 'App\Models\Transaction')
                                                <i class="fas fa-exchange-alt fa-2x text-warning"></i>
                                            @else
                                                <i class="fas fa-info-circle fa-2x text-secondary"></i>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                                <hr>
                                <div class="d-flex justify-content-between align-items-center pt-5">
                                    <div class="btn-toolbar mb-2 mb-md-0">
                                        <div class="btn-group me-2">
                                            <a href="{{ $activityLog->previousPageUrl() }}" class="btn btn-sm btn-primary">Previous</a>
                                        </div>
                                    </div>

                                    <div class="btn-toolbar mb-2 mb-md-0">
                                        <div class="btn-group me-2">
                                            <p class="page-info">{{ $activityLog->currentPage() }}</p>
                                        </div>
                                    </div>

                                    <div class="btn-toolbar mb-2 mb-md-0">
                                        <div class="btn-group me-2">
                                            <a href="{{ $activityLog->nextPageUrl() }}" class="btn btn-sm btn-primary">Next</a>
                                        </div>
                                    </div>
                                </div>


                        </ul>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <script src="{{asset('js/datatables-simple-demo.js')}}"></script>
    <script src="{{asset('js/scripts.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        var userChart = @json($userChart);
        var months = Object.keys(userChart);
        var counts = Object.values(userChart);

        var ctx = document.getElementById('myLineChart').getContext('2d');
        var myLineChart = new Chart(ctx, {
            type: 'line', // Line chart
            data: {
                labels: months,
                datasets: [{
                    label: 'User Count',
                    data: counts,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        type: 'category', // Use a category axis for the x-axis (months are categories)
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var transactionChart = @json($transactionChart);
        var monthsTrx = Object.keys(transactionChart);
        var countsTrx = Object.values(transactionChart);

        var trxCtx = document.getElementById('myLineTrxChart').getContext('2d');
        var myLineTrxChart = new Chart(trxCtx, {
            type: 'line', // Line chart
            data: {
                labels: monthsTrx,
                datasets: [{
                    label: 'Transaction Count',
                    data: countsTrx,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        type: 'category', // Use a category axis for the x-axis (months are categories)
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
