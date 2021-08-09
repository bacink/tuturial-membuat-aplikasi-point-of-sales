<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Grafik Pembelian {{ tanggal_indonesia($tanggal_awal, false) }} s/d {{ tanggal_indonesia($tanggal_akhir, false) }}</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="chart">
                    <!-- pembelian Chart Canvas -->
                    <canvas id="pembelianChart" style="height: 180px;"></canvas>
                </div>
                <!-- /.chart-responsive -->
            </div>
        </div>
        <!-- /.row -->
    </div>
</div>

@push('scripts')
<!-- ChartJS -->
<script src="{{ asset('AdminLTE-2/bower_components/chart.js/Chart.js') }}"></script>

<script>
$(function() {
    // Get context with jQuery - using jQuery's .get() method.
    var pembelianChartCanvas = $('#pembelianChart').get(0).getContext('2d');
    // This will get the first returned node in the jQuery collection.
    var pembelianChart = new Chart(pembelianChartCanvas);

    var pembelianChartData = {
        labels: {{ json_encode($data_tanggal) }},
        datasets: [
            {
                label: 'Pendapatan',
                fillColor           : 'rgba(60,141,188,0.9)',
                strokeColor         : 'rgba(60,141,188,0.8)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(60,141,188,1)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data: {{ json_encode($data_pembelian) }}
            }
        ]
    };

    var pembelianChartOptions = {
        pointDot : false,
        responsive : true
    };

    pembelianChart.Line(pembelianChartData, pembelianChartOptions);
});
</script>
@endpush