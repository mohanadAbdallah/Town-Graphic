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
                <h4><span class="font-weight-semibold">Show User {{$user->name}}</span></h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>


        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="/admin" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
                    <a href="{{route('users.index')}}" class="breadcrumb-item">Users</a>
                    <span class="breadcrumb-item active">{{$user->name}}</span>
                    <span class="breadcrumb-item active">Show</span>
                </div>

            </div>


        </div>
    </div>
    <!-- /page header -->


    <div class="content">

        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">User Details</h5>

            </div>

            <div class="card-body">

                <form class="rated" action="" method="post"
                      enctype="multipart/form-data">

                    <div class="row">

                        <div class="col-5 ">

                            <div class="form-group">
                                <label class="control-label font-weight-bold" for="name">Name:</label>
                                {{$user->name ?? '--' }}
                            </div>

                            <div class="form-group">
                                <label class="control-label font-weight-bold" for="email">Email:</label>
                                {{$user->email ?? '--' }}
                            </div>

                            <div class="form-group">
                                <label class="control-label font-weight-bold" for="mobile">Mobile:</label>
                                {{$user->mobile ?? '--' }}
                            </div>

                        </div>

                        <div class="col-5 offset-md-1">
                            <div class="form-group">
                                <label class="control-label font-weight-bold">Role:</label>
                                {{ $user->getRoleNames()[0] ?? '' }}
                                <small class="form-control-feedback"></small>
                            </div>
                            <div class="form-group">
                                <label class="control-label font-weight-bold">Login method:</label>
                                {{ $user->login_method ?? '--' }}
                                <small class="form-control-feedback"></small>
                            </div>
                            <div class="form-group">
                                <label class="control-label font-weight-bold">Is active:</label>
                                {{ $user->is_active  ?? '--' }}
                                <small class="form-control-feedback"></small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-body">
                                <div class="media">
                                    <div class="mr-3 align-self-center">
                                        <i class="icon-point-up icon-3x text-success-400"></i>
                                    </div>

                                    <div class="media-body text-right">
                                        <h3 class="font-weight-semibold mb-0">
                                            {{ $user->userStatics->points_count ?? '0' }}
                                        </h3>
                                        <span class="text-uppercase font-size-sm text-muted">Points</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-body">
                                <div class="media">
                                    <div class="mr-3 align-self-center">
                                        <i class="icon-question4 icon-3x text-indigo-400"></i>
                                    </div>

                                    <div class="media-body text-right">
                                        <h3 class="font-weight-semibold mb-0">{{ $user->userStatics->quizzes_count ?? '0' }}</h3>
                                        <span class="text-uppercase font-size-sm text-muted">Quizzes</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-body">
                                <div class="media">
                                    <div class="media-body">
                                        <h3 class="font-weight-semibold mb-0">{{ $user->userStatics->tips_count ?? '0' }}</h3>
                                        <span class="text-uppercase font-size-sm text-muted">Tips</span>
                                    </div>
                                    <div class="ml-3 align-self-center">
                                        <i class="icon-notification2 icon-3x text-blue-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-body">
                                <div class="media">
                                    <div class="media-body">
                                        <h3 class="font-weight-semibold mb-0">{{ $user->userStatics->challenges_count ?? '0' }}</h3>
                                        <span class="text-uppercase font-size-sm text-muted">Challenges</span>
                                    </div>

                                    <div class="ml-3 align-self-center">
                                        <i class="icon-stack4 icon-3x text-danger-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-body">
                                <div class="media">
                                    <div class="mr-3 align-self-center">
                                        <i class="icon-calendar2 icon-3x text-success-400"></i>
                                    </div>

                                    <div class="media-body text-right">
                                        <h3 class="font-weight-semibold mb-0">{{ $user->userStatics->events_count ?? '0' }}</h3>
                                        <span class="text-uppercase font-size-sm text-muted">Events</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-body">
                                <div class="media">
                                    <div class="mr-3 align-self-center">
                                        <i class="icon-envelop5 icon-3x text-indigo-400"></i>
                                    </div>

                                    <div class="media-body text-right">
                                        <h3 class="font-weight-semibold mb-0">{{ $user->userStatics->invites_count ?? '0' }}</h3>
                                        <span class="text-uppercase font-size-sm text-muted">Affiliates </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-body">
                                <div class="media">
                                    <div class="media-body">
                                        <h3 class="font-weight-semibold mb-0">{{ $user->userStatics->shares_count ?? '0' }}</h3>
                                        <span class="text-uppercase font-size-sm text-muted">Shares</span>
                                    </div>
                                    <div class="ml-3 align-self-center">
                                        <i class="icon-share2 icon-3x text-blue-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-body">
                                <div class="media">
                                    <div class="media-body">
                                        <h3 class="font-weight-semibold mb-0">{{ $user->userBadge->count()}}</h3>
                                        <span class="text-uppercase font-size-sm text-muted">Badges</span>
                                    </div>

                                    <div class="ml-3 align-self-center">
                                        <i class="icon-medal2 icon-3x text-danger-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-body">
                                <div class="media">
                                    <div class="media-body">
                                        <h3 class="font-weight-semibold mb-0">{{ \Carbon\Carbon::parse($user->created_at)->format('d-m-Y') }}</h3>
                                        <span class="text-uppercase font-size-sm text-muted">Created at</span>
                                    </div>

                                    <div class="ml-3 align-self-center">
                                        <i class="icon-user-plus icon-3x text-danger-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12" style="">
                            <div class="card">
                                <div class="card-header header-elements-inline">
                                    <h6 class="media-title font-weight-semibold">User addresses</h6>

                                    <div class="header-elements">
                                        <div class="list-icons">
                                            <a class="list-icons-item" data-action="collapse"></a>

                                        </div>
                                    </div>
                                </div>
                                @isset($user->userAddresses)
                                    <div class="card-body" style="height: 296px;overflow: auto;">
                                        <div class="row">
                                            <div class="list-feed col-md-12">

                                                <div class="list-feed-item list-feed-rhombus list-feed-solid ">
                                                    <div
                                                        class="text-muted">{{$user->userAddresses->created_at ?? ''}}</div>
                                                    <div>{{$user->userAddresses->city->name_ar ?? ''}}
                                                        - {{$user->userAddresses->district->name_ar ?? ''}}</div>
                                                    <div class="text-muted">
                                        <span style="color: #838383;"><i
                                                class="icon-arrow-small-right"></i>Full address : {{$user->userAddresses->full_address ?? ''}}  </span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                @endisset
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>
@endsection
