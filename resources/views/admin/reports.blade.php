@extends('layouts.adminlayout')
@section('page-title', 'Reports')
@section('breadcrumb', 'Reports')

@push('page-data')
<script>
    window.adminData = window.adminData || {};
    window.adminData.revenue = null;
    window.adminData.category = null;
    window.adminData.weekly = null;
    window.adminData.growth = null;
    window.adminData.radar = null;
    window.adminData.revExp = {
        labels:   ['Jan','Feb','Mar','Apr','May','Jun'],
        revenue:  [42100,55300,48900,70400,65200,80100],
        expenses: [28400,34100,31200,40800,39400,46200]
    };
    window.adminData.source = {
        labels: ['Organic Search','Direct','Social Media','Email','Referral'],
        data:   [38,24,18,12,8]
    };
</script>
@endpush

@section('content')

<div class="row g-3 mb-4">
    @php
    $kpis = [
        ['label'=>'Gross Revenue','val'=>'$94.2K','change'=>'+18.3% YoY','up'=>true,'icon'=>'bi-currency-dollar','si'=>'si-pink'],
        ['label'=>'Net Profit',   'val'=>'$31.8K','change'=>'+9.1% margin','up'=>true,'icon'=>'bi-graph-up',     'si'=>'si-green'],
        ['label'=>'Avg Order Val','val'=>'$73.40','change'=>'+5.2%','up'=>true,'icon'=>'bi-cart-check-fill','si'=>'si-blue'],
        ['label'=>'Conv. Rate',   'val'=>'3.84%', 'change'=>'-0.2%','up'=>false,'icon'=>'bi-percent',       'si'=>'si-orange'],
    ];
    @endphp

    @foreach($kpis as $k)
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon {{ $k['si'] }}"><i class="bi {{ $k['icon'] }}"></i></div>
            <div class="stat-label">{{ $k['label'] }}</div>
            <div class="stat-value">{{ $k['val'] }}</div>
            <div class="stat-change {{ $k['up'] ? 'ch-up' : 'ch-down' }}">
                <i class="bi {{ $k['up'] ? 'bi-arrow-up-right' : 'bi-arrow-down-right' }}"></i> {{ $k['change'] }}
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card-w">
            <div class="sec-header">
                <div><div class="sec-title">Revenue vs Expenses</div><div class="sec-sub">Last 6 months</div></div>
            </div>
            <div class="ch-wrap" style="height:220px;"><canvas id="revExpChart"></canvas></div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card-w">
            <div class="sec-header">
                <div><div class="sec-title">Traffic Sources</div><div class="sec-sub">Where orders originate</div></div>
            </div>
            <div class="ch-wrap" style="height:220px;"><canvas id="sourceChart"></canvas></div>
        </div>
    </div>
</div>

<div class="card-w">
    <div class="sec-header">
        <div><div class="sec-title">Monthly Revenue Report</div><div class="sec-sub">January – June 2025</div></div>
        <button class="btn-o"><i class="bi bi-download"></i> Download PDF</button>
    </div>
    <div style="overflow-x:auto;">
    <table class="tbl">
        <thead>
            <tr><th>Month</th><th>Orders</th><th>Revenue</th><th>Expenses</th><th>Net Profit</th><th>Growth</th></tr>
        </thead>
        <tbody>
        @php
        $months=[
            ['m'=>'January', 'o'=>98, 'r'=>'$42,100','e'=>'$28,400','p'=>'$13,700','g'=>'+5.2%', 'up'=>true],
            ['m'=>'February','o'=>115,'r'=>'$55,300','e'=>'$34,100','p'=>'$21,200','g'=>'+31.4%','up'=>true],
            ['m'=>'March',   'o'=>107,'r'=>'$48,900','e'=>'$31,200','p'=>'$17,700','g'=>'-11.5%','up'=>false],
            ['m'=>'April',   'o'=>142,'r'=>'$70,400','e'=>'$40,800','p'=>'$29,600','g'=>'+43.9%','up'=>true],
            ['m'=>'May',     'o'=>138,'r'=>'$65,200','e'=>'$39,400','p'=>'$25,800','g'=>'-7.4%', 'up'=>false],
            ['m'=>'June',    'o'=>167,'r'=>'$80,100','e'=>'$46,200','p'=>'$33,900','g'=>'+22.8%','up'=>true],
        ];
        @endphp
        @foreach($months as $m)
        <tr>
            <td style="font-weight:600;">{{ $m['m'] }}</td>
            <td>{{ $m['o'] }}</td>
            <td style="font-weight:700;color:#1f9c4a;">{{ $m['r'] }}</td>
            <td style="color:var(--danger);">{{ $m['e'] }}</td>
            <td style="font-weight:700;color:var(--dark);">{{ $m['p'] }}</td>
            <td style="font-weight:700;color:{{ $m['up']?'#1f9c4a':'var(--danger)' }};">{{ $m['g'] }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>

@endsection
