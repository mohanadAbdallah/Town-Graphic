@extends('admin.layouts.app')
@section('js_css_header')
    <script src="{{asset('portal/global_assets/js/plugins/visualization/echarts/echarts.min.js')}}"></script>

    <script src="{{asset('portal/global_assets/js/demo_charts/echarts/light/pies/pie_basic.js')}}"></script>
    <script src="{{asset('portal/global_assets/js/demo_charts/echarts/light/pies/pie_donut.js')}}"></script>
    <script src="{{asset('portal/global_assets/js/demo_charts/echarts/light/pies/pie_nested.js')}}"></script>
    <script src="{{asset('portal/global_assets/js/demo_charts/echarts/light/pies/pie_rose.js')}}"></script>
    <script src="{{asset('portal/global_assets/js/demo_charts/echarts/light/pies/pie_rose_labels.js')}}"></script>
    <script src="{{asset('portal/global_assets/js/demo_charts/echarts/light/pies/pie_levels.js')}}"></script>
    <script src="{{asset('portal/global_assets/js/demo_charts/echarts/light/pies/pie_timeline.js')}}"></script>
    <script src="{{asset('portal/global_assets/js/demo_charts/echarts/light/pies/pie_multiple.js')}}"></script>
    <script src="{{asset('portal/global_assets/js/plugins/visualization/d3/d3.min.js')}}"></script>
@endsection
@section('content')

    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><span class="font-weight-semibold">Main</span></h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>


        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="#" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
                    <span class="breadcrumb-item active">Dashboard</span>
                </div>

                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>

            <div class="header-elements d-none">
                <div class="breadcrumb justify-content-center">


                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->


    <div class="content">
        <style>
            .gm-style-iw-d {
                overflow: auto !important;
            }

            .gm-style-iw {
                background: #f3ffe5 !important;
            }
        </style>
        <!-- Basic card -->

        <div class="row">
            @hasanyrole('admin')
            <div class="col-sm-6 col-xl-4">
                <div class="card card-body">
                    <div class="media">
                        <div class="media-body" style="">
                            <h6 class="media-title font-weight-semibold">Number of users</h6>
                            <h3><b>{{$appUsers}}</b></h3>

                        </div>
                    </div>
                </div>
            </div>
            @endhasanyrole
            <div class="col-sm-6 col-xl-4">
                <div class="card card-body">
                    <div class="media">
                        <div class="media-body" style="">
                            <h6 class="media-title font-weight-semibold">Number of orders</h6>
                            <h3><b>{{$orders}}</b></h3>

                        </div>
                    </div>
                </div>
            </div>
            @hasanyrole('admin')
            <div class="col-sm-6 col-xl-4">
                <div class="card card-body">
                    <div class="media">
                        <div class="media-body" style="">
                            <h6 class="media-title font-weight-semibold">Number of agents</h6>
                            <h3><b>{{$agents}}</b></h3>

                        </div>
                    </div>
                </div>
            </div>
            @endhasanyrole
            @hasanyrole('agent')
            <div class="col-sm-6 col-xl-4">
                <div class="card card-body">
                    <div class="media">
                        <div class="media-body" style="">
                            <h6 class="media-title font-weight-semibold">Number of approved orders</h6>
                            <h3><b>{{$approvedOrders}}</b></h3>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="card card-body">
                    <div class="media">
                        <div class="media-body" style="">
                            <h6 class="media-title font-weight-semibold">Number of canceled orders</h6>
                            <h3><b>{{$canceledOrders}}</b></h3>

                        </div>
                    </div>
                </div>
            </div>
            @endhasanyrole
        </div>

        <div class="row">
            <div class="col-sm-6 col-xl-4">
                <div class="card card-body bg-blue-400 has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mb-0">{{$todayIncome}}</h3>
                            <span class="text-uppercase font-size-xs font-weight-bold">Daily income</span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-coins icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="card card-body bg-success-400 has-bg-image">
                    <div class="media">
                        <div class="media-body ">
                            <h3 class="mb-0">{{$monthIncome}}</h3>
                            <span class="text-uppercase font-size-xs font-weight-bold">Month income</span>
                        </div>

                        <div class="mr-3 align-self-center">
                            <i class="icon-coin-dollar icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="card card-body bg-danger-400 has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mb-0">{{$yearIncome}}</h3>
                            <span class="text-uppercase font-size-xs font-weight-bold">Year income</span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-cash icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            @hasanyrole('admin')
            <div class="col-sm-12 col-xl-12">
                <div class="card card-body  has-bg-image">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Agent</th>
                            <th>No. orders</th>
                            <th>Current orders</th>
                            <th>Payment received</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($agentsOrders as $item)
                            @if($item->hasRole('agent'))
                                <tr>
                                    <td>{{$item->name}}</td>
                                    <td>
                                        <span class="badge badge-danger">
                                            {{$item->order->count()}}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                        {{$item->order->wherein('status',[0,1])->count()}}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-warning">
                                        {{$item->order->where('status', 2)->sum('total_amount_with_fees')}}
                                        </span>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @endhasanyrole
        </div>
    </div>
@endsection

@section('js_code')


@endsection
