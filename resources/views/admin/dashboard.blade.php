@extends('admin.layouts.app')
@section('title')
    @lang('Dashboard')
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row admin-fa_icon dashboard__card">
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium">{{$domain}}</h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">@lang('Total Domain')</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted"><i class="fa fa-globe fa-2x"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium">{{$tags}}</h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">@lang('Tags')</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                               <span class="opacity-7 text-purple"><i class="fa fa-tags fa-2x"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium">{{$iplist}}</h2>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">@lang('Today IP Block')</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                               <span class="opacity-7 text-secondary"><i class="fa fa-lock fa-2x"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium">{{$wallpapers}}</h2> / <code>{{$wallpapers_inactive}}</code>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">@lang('Wallpapers')</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-danger"><i class="fa fa-image fa-2x"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium">{{$ringtones}}</h2> / <code>{{$ringtones_inactive}}</code>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">@lang('Ringtones')</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                               <span class="opacity-7 text-warning"><i class="fa fa-bell fa-2x"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="card shadow border-right">
                    <div class="card-body">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="text-dark mb-1 font-weight-medium">{{$musics}}</h2> / <code>{{$musics_inactive}}</code>
                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">@lang('Musics')</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                               <span class="opacity-7 text-success"><i class="fa fa-music fa-2x"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-9">
                <div class="card shadow">
                    <div class="card-body p-1">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="card-title pl-1 py-1 mt-2 ml-2">@lang("This Month's Summary")</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="date-picker-space">
                                    <input class="form-control " id="time_range">
                                </div>
                            </div>
                        </div>
                        <div>
                            <div id="yearly_redirect_overview-container" class="pt-2">
                                <canvas id="yearly_redirect_chart_canvas"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-3">
                <div class="row">
                    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-12">
                        <div class="card ">
                            <div class="card-body  p-1">
                                <h4 class="card-title pl-1 py-1 mt-2 ml-2">Platform</h4>
                                <div>
                                    <div id="platform-overview-container" class="pt-2">
                                        <canvas id="platform_overview"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-12">
                        <div class="card ">
                            <div class="card-body  p-1">
                                <h4 class="card-title pl-1 py-1 mt-2 ml-2">Country</h4>
                                <div>
                                    <div id="country-overview-container" class="pt-2">
                                        <canvas id="country_overview"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


    <script>
        "use strict";
        let start_date
        let end_date
        let isPickerApply = false
        let timeRange

        document.addEventListener("DOMContentLoaded", function () {
            initDashboardDatePicker();
        });

        function initDashboardDatePicker() {
            timeRange = $('#time_range');

            if (!timeRange.length) {
                return;
            }

            start_date = moment().startOf('month')
            end_date = moment().endOf('month');
            setDbDatepickerValue(start_date, end_date);
            const last_month = moment().startOf('month').subtract(1, 'days')

            timeRange.daterangepicker({
                startDate: start_date,
                endDate: end_date,
                opens: 'left',
                showDropdowns: true,
                autoUpdateInput: false,
                locale: {
                    customRangeLabel: 'custom',
                    applyLabel: 'apply',
                    cancelLabel: 'cancel',
                    fromLabel: 'from',
                    toLabel: 'to',
                    monthNames: [
                        'Jan',
                        'Feb',
                        'Mar',
                        'Apr',
                        'May',
                        'Jun',
                        'Jul',
                        'Aug',
                        'Sep',
                        'Oct',
                        'Nov',
                        'Dec'
                    ],
                    daysOfWeek: [
                        'SUN',
                        'MON',
                        'TUE',
                        'WED',
                        'THU',
                        'FRI',
                        'SAT'
                    ],
                },
                ranges: {
                    ['Today']: [moment(), moment()],
                    ['This Week']: [moment().startOf('week'), moment().endOf('week')],
                    ['Last Week']: [
                        moment().startOf('week').subtract(7, 'days'),
                        moment().startOf('week').subtract(1, 'days')],
                    ['This Month']: [start_date, end_date],
                    ['Last Month']: [
                        last_month.clone().startOf('month'),
                        last_month.clone().endOf('month')],
                },
            }, setDbDatepickerValue);
            loadPlatformViewStatus(start_date.format('YYYY-MM-D'), end_date.format('YYYY-MM-D'))
            loadCountryViewStatus(start_date.format('YYYY-MM-D'), end_date.format('YYYY-MM-D'))
            loadYearlyRedirectChat(start_date.format('YYYY-MM-D'), end_date.format('YYYY-MM-D'));


            timeRange.on('apply.daterangepicker', function (ev, picker) {
                isPickerApply = true
                start_date = picker.startDate.format('YYYY-MM-D')
                end_date = picker.endDate.format('YYYY-MM-D');
                console.log(start_date)
                console.log(end_date)
                loadYearlyRedirectChat(start_date, end_date);
                loadPlatformViewStatus(start_date, end_date);
                loadCountryViewStatus(start_date, end_date);
            });
        }

        function setDbDatepickerValue(start_date, end_date) {
            timeRange.val(start_date.format('MMM D, YYYY') + ' - ' + end_date.format('MMM D, YYYY'));
        }

        function loadYearlyRedirectChat(startDate, endDate) {
            $.ajax({
                type: 'GET',
                url: '{{route('admin.dashboard.getYearlyData')}}',
                dataType: 'json',
                data: {
                    start_date: startDate,
                    end_date: endDate,
                },
                cache: false,
            }).done(prepareYearlyRedirectViewChart);
        }

        function loadPlatformViewStatus(startDate, endDate){
            $.ajax({
                type: 'GET',
                url: '{{route('admin.dashboard.getPlatformData')}}',
                dataType: 'json',
                data: {
                    start_date: startDate,
                    end_date: endDate,
                },
                cache: false,
            }).done(preparePlatformViewStatusChart);
        }

        function loadCountryViewStatus(startDate, endDate){
            $.ajax({
                type: 'GET',
                url: '{{route('admin.dashboard.getCountryData')}}',
                dataType: 'json',
                data: {
                    start_date: startDate,
                    end_date: endDate,
                },
                cache: false,
            }).done(prepareCountryViewStatusChart);
        }

        function preparePlatformViewStatusChart(result){
            $('#platform-overview-container').html('');
            let data = result;
            if (data.dataPoints.every(value => value === 0)) {
                $('#platform-overview-container').empty();
                $('#platform-overview-container').
                append('<div align="center" class="no-record">' +
                    'No Record Found' +
                    '</div>');
                return true;
            } else {
                $('#platform-overview-container').html('');
                $('#platform-overview-container').
                append('<canvas id="platform_overview" style="max-height: 350px; display: block; margin: auto;"></canvas>');
            }
            let ctx = document.getElementById('platform_overview').getContext('2d');

            let pieChartData = {
                labels: data.labels,
                datasets: [
                    {
                        data: data.dataPoints,
                        backgroundColor: generateBackgroundColors(data.dataPoints.length),
                        // backgroundColor: ['#0e00fa', '#48ff00', '#eebf04', '#ff0000'],
                    }],
            };

            window.myBar = new Chart(ctx, {
                type: 'doughnut',
                data: pieChartData,
                options: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        boxWidth: 9,
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return ' '+context.label+': '+context.formattedValue;
                                },
                            },
                        },
                    },
                },
            });
        }

        function prepareCountryViewStatusChart(result){
            $('#country-overview-container').html('');
            let data = result;
            if (data.dataPoints.length === 0) {
                $('#country-overview-container').empty();
                $('#country-overview-container').
                append('<div align="center" class="no-record">' +
                    'No Record Found' +
                    '</div>');
                return true;
            } else {
                $('#country-overview-container').html('');
                $('#country-overview-container').
                append('<canvas id="country_overview"  style="max-height: 350px; display: block; margin: auto;"></canvas>');
            }
            let ctx = document.getElementById('country_overview').getContext('2d');
            let pieChartData = {
                labels: data.labels,
                datasets: [
                    {
                        data: data.dataPoints,
                        backgroundColor: generateBackgroundColors(data.dataPoints.length),
                    }],
            };

            window.myBar = new Chart(ctx, {
                type: 'doughnut',
                data: pieChartData,
                options: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        boxWidth: 9,
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return ' '+context.label+': '+context.formattedValue;
                                },
                            },
                        },
                    },
                },
            });
        }

        function prepareYearlyRedirectViewChart(result){
            $('#yearly_redirect_overview-container').html('');
            let data = result;
            if (data.total_login.every(value => value === 0)) {
                $('#yearly_redirect_overview-container').empty();
                $('#yearly_redirect_overview-container').
                append('<div align="center" class="no-record">' +
                    'No Record Found' +
                    '</div>');
                return true;
            } else {
                $('#yearly_redirect_overview-container').html('');
                $('#yearly_redirect_overview-container').
                append(
                    '<canvas id="yearly_redirect_chart_canvas"></canvas>');
            }
            let ctx = document.getElementById('yearly_redirect_chart_canvas').
            getContext('2d');
            ctx.canvas.height = 150;

            let myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: data.month, // Name the series
                            data: data.total_login, // Specify the data values array
                            fill: false,
                            borderColor: '#2196f3', // Add custom color border (Line)
                            backgroundColor: '#2196f3', // Add custom color background (Points and Fill)
                            borderWidth: 2, // Specify bar border width
                        }],
                },
                options: {
                    elements: {
                        line: {
                            tension: 0.2,
                        },
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: false,
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return ''+context.formattedValue;
                                },
                            },
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                            },
                            ticks: {
                                min: 0,
                                // stepSize: 500,
                                callback: function (label) {
                                    return label;
                                },
                            },
                        },
                        x: {
                            beginAtZero: true,
                            grid: {
                                display: false,
                            },
                        },
                    },
                },
            });
        }

        function generateBackgroundColors(length) {
            let backgroundColors = [
                "#22ca80",
                "#f9dd7e",
                "#ff6f62",
                "#05ffe4",
                "#6c757d",
                "#8b6ef3",
                "#f34da3"];

            return backgroundColors;
        }

    </script>
@endpush
