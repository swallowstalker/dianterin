@extends("layouts.admin")

@section("style")

<!-- JQuery-UI Theme CSS -->
<link href="{!! asset("/") !!}bower_components/jquery-ui/themes/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css">

@endsection


@section("javascript")

<!-- JQuery-UI JavaScript -->
<script src="{!! asset("/") !!}bower_components/jquery-ui/jquery-ui.min.js"></script>

<script src="{!! asset("/") !!}bower_components/Chart.js/Chart.min.js"></script>

<script src="{!! asset("/") !!}bower_components/jquery.mtz.monthpicker/jquery.mtz.monthpicker.js"></script>

<script type="text/javascript">

    var chart;

    function loadGraph(monthYearData) {

        if (chart != null) {
            chart.destroy();
        }

        $.ajax({
            url: baseURL + '/admin/profit/data/'+ monthYearData,
            type: "GET",
            success: function (data) {

                var graphData = {
                    labels: data.labels,
                    datasets: [
                        {
                            label: "Profit",
                            fillColor: "rgba(151,187,205,0.2)",
                            strokeColor: "rgba(151,187,205,1)",
                            pointColor: "rgba(151,187,205,1)",
                            pointStrokeColor: "#fff",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(151,187,205,1)",
                            data: data.values
                        }
                    ]
                };

                var ctx = document.getElementById("profit-graph").getContext("2d");
                chart = new Chart(ctx).Line(graphData);
            }
        });
    }

    $(document).ready(function() {

        loadGraph("");

        $("input[name=month-filter]").monthpicker({
            pattern: "yyyy-mm",
            selectedYear: "{{ date("Y") }}",
            startYear: 2014
        });

        $("input[name=month-filter]").change(function () {
            loadGraph($(this).val());
        });
    });
</script>


@endsection


@section("content")


<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Profit</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">


                <div class="panel-heading">
                    Profit
                    <input name="month-filter" value="{{ date("Y-m") }}" />
                </div>
                <div class="panel-body">
                    <canvas id="profit-graph" style="width: 100%; height: 350px;"></canvas>
                </div>

            </div>
        </div>
    </div>
</div>



@endsection