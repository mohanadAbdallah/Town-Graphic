@extends('admin.layouts.app')

@section('content')

    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><span class="font-weight-semibold">Create </span></h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>


        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="/admin" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
                    <a href="{{route('users_groups_permissions.index')}}" class="breadcrumb-item">Permissions</a>
                    <span class="breadcrumb-item active">Create</span>
                </div>

            </div>


        </div>
    </div>
    <!-- /page header -->


    <div class="content">

        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">Permissions Details</h5>

            </div>

            <div class="card-body">


                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                    <form class=" m-t-40 " method="post"
                          action="{{route('users_groups_permissions.store')}}">
                        {{csrf_field()}}

                        @if(isset($item))
                            <input name="item_id" type="hidden" value="{{$item->id}}">
                        @endif
                        <div class="row">
                            <div class="col-12">

                                <div class="form-group">
                                    <label class="control-label">Name</label>
                                    <input value="{{isset($item) ? $item->name : ''}}" required name="name"
                                           class="form-control" type="text">
                                    <small class="form-control-feedback"></small>
                                </div>


                            </div>


                        </div>


                        <div class="form-group col-3">

                            <button class="btn btn-info" type="submit">Submit</button>
                        </div>

                    </form>
            </div>
        </div>


    </div>
@endsection
