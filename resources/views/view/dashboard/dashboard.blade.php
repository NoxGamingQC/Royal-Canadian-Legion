@extends('layout.app')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <br />
            <h1>Tableau de bord</h1>
            <hr />
            <br />
        </div>
        <div class="col-md-12">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card" style="margin-bottom:5%">
                            <div class="card-header">
                                <h6 class="card-title">Commande totales</h6>
                            </div>
                            <div class="card-body">
                                <h2 class="card-title text-center">{{$total_transactions ? count($total_transactions) : '0'}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card" style="margin-bottom:5%">
                            <div class="card-header">
                                <h6 class="card-title">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <span>Membres en règles</span>
                                        </div>
                                        <div class="col-md-2">
                                            <a href="/{{Auth::user()->getUserCommand() . '-' . Auth::user()->getUserBranch()}}/members"><i class="fa fa-external-link" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                </h6>
                            </div>
                            <div class="card-body">
                                <h2 class="card-title text-center">{{$active_member_count}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card" style="margin-bottom:5%">
                            <div class="card-header">
                                <h6 class="card-title">Revenue</h6>
                            </div>
                            <div class="card-body">
                                @php
                                    $formatter = new NumberFormatter('fr_CA',  NumberFormatter::CURRENCY); 
                                @endphp
                                <h2 class="card-title text-center">{{$total_transactions_sum ? $formatter->formatCurrency($total_transactions_sum, 'CAD') : $formatter->formatCurrency(0, 'CAD')}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card" style="margin-bottom:5%">
                            <div class="card-header">
                                <h6 class="card-title">Dépenses</h6>
                            </div>
                            <div class="card-body">
                                @php
                                    $formatter = new NumberFormatter('fr_CA',  NumberFormatter::CURRENCY); 
                                @endphp
                                <h2 class="card-title text-center">{{$formatter->formatCurrency(0, 'CAD')}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card" style="margin-bottom:5%">
                            <div class="card-header">
                                <h6 class="card-title">Ventes totales mensuel</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="monthlyTransaction" style="width:100%;max-width:700px; max-height:300px;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card" style="margin-bottom:5%">
                            <div class="card-header">
                                <h6 class="card-title">Ventes par catégories</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="mostSoldCategories" style="width:100%;max-width:700px; max-height:300px;"></canvas>
                            </div>
                        </div>
                    </div>
                     <div class="col-md-6">
                        <div class="card" style="margin-bottom:5%">
                            <div class="card-header">
                                <h6 class="card-title">Ventes et dépenses</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="sellAndExpenses" style="width:100%;max-width:700px; max-height:300px;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card" style="margin-bottom:5%">
                            <div class="card-header">
                                <h6 class="card-title">Catégories les plus vendus en {{date('Y')}}</h6>
                            </div>
                            <div class="card-body">
                                <ol>
                                    @if($top_10_categories)
                                        @foreach($top_10_categories as $key => $value)
                                            @php
                                                $formatter = new NumberFormatter('fr_CA',  NumberFormatter::CURRENCY); 
                                            @endphp
                                            <li>{{ str_replace(array("\\"), '', $value['name']) }} <small>({{$formatter->formatCurrency($value['sum'], 'CAD')}})</small></li>
                                        @endforeach
                                    @else
                                        <h3 class="card-title">Aucune données pour l'instant<h3>
                                    @endif
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card" style="margin-bottom:5%">
                            <div class="card-header">
                                <h6 class="card-title">Articles les plus vendus en {{date('Y')}}</h6>
                            </div>
                            <div class="card-body">
                                <ol>
                                    @if($top_10_items)
                                        @foreach($top_10_items as $key => $value)
                                            @php
                                                $formatter = new NumberFormatter('fr_CA',  NumberFormatter::CURRENCY); 
                                            @endphp
                                            <li>{{ str_replace(array("\\"), '', $value['name']) }} <small>({{$formatter->formatCurrency($value['sum'], 'CAD')}})</small></li>
                                        @endforeach
                                    @else
                                        <h3 class="card-title">Aucune données pour l'instant</h3>
                                    @endif
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card" style="margin-bottom:5%">
                            <div class="card-header">
                                <h6>Finances</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="finances" style="width:100%;max-width:700px; max-height:300px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const monthlyTransactionCanvas = document.getElementById('monthlyTransaction').getContext('2d');
    const monthlyTransaction = new Chart(monthlyTransactionCanvas, {
        type: 'line', // e.g., 'line', 'pie', 'doughnut', 'scatter'
        data: {
        labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet','Août', 'Septembre', 'Octobre', 'Novembre' , 'Décembre'],
        datasets: [{
                label: [new Date().getFullYear()],
                data: [{{implode(',', $transactions_sum_by_month)}}],
                pointRadius: 5,
                pointHoverRadius: 10,
                backgroundColor: [
                    @foreach($transactions_color_by_month as $key => $value)
                     '{{$value}}',
                    @endforeach
                ],
                borderColor: [
                    'rgb(0,0,0)',
                ],
                borderWidth: 1,
                tension: 0.4
            },{
                label: [new Date().getFullYear() -1],
                data: [{{implode(',', $transactions_sum_by_month_last_year)}}],
                pointRadius: 5,
                pointHoverRadius: 10,
                backgroundColor: [
                    'rgb(250, 100, 100,0.2)',
                ],
                borderColor: [
                    'rgb(250, 100, 100, 0.5)',
                ],
                borderWidth: 1,
                tension: 0.4
            },{
                label: [new Date().getFullYear() -2],
                data: [{{implode(',', $transactions_sum_by_month_2_years_ago)}}],
                pointRadius: 5,
                pointHoverRadius: 10,
                backgroundColor: [
                    'rgb(175, 175, 175,0.5)',
                ],
                borderColor: [
                    'rgb(175, 175, 175, 1)',
                ],
                borderWidth: 1,
                tension: 0.4
            }
        ]
        },
        options: {
            spanGaps: true,
            plugins: {
                title: {
                    display: false,
                },
                legend: {
                    display: true,
                    labels: {
                        generateLabels: (chart) => {
                            // Generate the labels with the default function
                            const labels = Chart.defaults.plugins.legend.labels.generateLabels(chart);
                            labels[0].fillStyle =  'rgb(0, 200, 70)'
                            
                            return labels;
                        },
                    }
                }
            },
            scales: {
                yAxes: [{
                ticks: {
                    beginAtZero: true
                }
                }]
            }
        }
    });

    const mostSoldCategoriesCanvas = document.getElementById('mostSoldCategories').getContext('2d');
    const mostSoldCategories = new Chart(mostSoldCategoriesCanvas, {
        type: 'pie', // e.g., 'line', 'pie', 'doughnut', 'scatter'
        data: {
        labels: ("{!! implode(',', $categories_name)!!}").split(','),
        datasets: [{
            label: 'Catégories les plus vendus',
            data: ('{{implode(',', $categories_sum)}}').split(','),
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(64, 255, 64, 0.2)'
                ],
                borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgb(109, 255, 64)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Ventes annuelles par catégories',
                }
            }
        }
    });

    const sellAndExpensesCanvas = document.getElementById('sellAndExpenses').getContext('2d');
    const sellAndExpenses = new Chart(sellAndExpensesCanvas, {
        type: 'line',
        data: {
        labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet','Août', 'Septembre', 'Octobre', 'Novembre' , 'Décembre'],
        datasets: [{
                label: 'Dépenses',
                data: [
                    @for($i = 0; $i < date('m'); $i++)
                        0,
                    @endfor
                ],
                pointRadius: 5,
                pointHoverRadius: 10,
                backgroundColor: [
                    'rgba(196, 12, 12, 0.2)'
                ],
                borderColor: [
                    'rgba(196, 12, 12, 1)',
                ],
                borderWidth: 1,
                tension: 0.4,
                fill:false,
            },{
                label: 'Ventes',
                data: [{{implode(',', $transactions_sum_by_month)}}],
                pointRadius: 5,
                pointHoverRadius: 10,
                backgroundColor: [
                    'rgba(18, 196, 12, 0.2)'
                ],
                borderColor: [
                    'rgba(18, 196, 12, 1)',
                ],
                borderWidth: 1,
                tension: 0.4,
                fill:true,
            },
        ]
        },
        options: {
            spanGaps: true,
            plugins: {
                title: {
                    display: false,
                },
            },
            scales: {
                yAxes: [{
                ticks: {
                    beginAtZero: true
                }
                }]
            }
        }
    });

    const financesCanvas = document.getElementById('finances').getContext('2d');
    const finances = new Chart(financesCanvas, {
        type: 'line',
        data: {
        labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet','Août', 'Septembre', 'Octobre', 'Novembre' , 'Décembre'],
        datasets: [{
                label: 'Coquelicot',
                data: [
                    @foreach($finances['poppy'] as $key => $value)
                        {{$value ?  $value : 'null'}},
                    @endforeach
                ],
                pointRadius: 5,
                pointHoverRadius: 10,
                backgroundColor: [
                    'rgba(196, 12, 12, 0.2)'
                ],
                borderColor: [
                    'rgba(196, 12, 12, 1)',
                ],
                borderWidth: 1,
                tension: 0.4,
                fill:false,
            },{
                label: 'Chèque',
                data: [
                    @foreach($finances['chequing'] as $key => $value)
                        {{$value ?  $value : 'null'}},
                    @endforeach
                ],
                pointRadius: 5,
                pointHoverRadius: 10,
                backgroundColor: [
                    'rgba(18, 196, 12, 0.2)'
                ],
                borderColor: [
                    'rgba(18, 196, 12, 1)',
                ],
                borderWidth: 1,
                tension: 0.4,
                fill:false,
            },{
                label: 'Épargne',
                data: [
                    @foreach($finances['saving'] as $key => $value)
                        {{$value ?  $value : 'null'}},
                    @endforeach
                ],
                pointRadius: 5,
                pointHoverRadius: 10,
                backgroundColor: [
                    'rgba(12, 46, 196, 0.2)'
                ],
                borderColor: [
                    'rgba(12, 15, 196, 1)',
                ],
                borderWidth: 1,
                tension: 0.4,
                fill:false,
            },
        ]
        },
        options: {
            spanGaps: true,
            plugins: {
                title: {
                    display: false,
                },
            },
            scales: {
                yAxes: [{
                ticks: {
                    beginAtZero: true
                }
                }]
            }
        }
    });
    
</script>  
@endsection