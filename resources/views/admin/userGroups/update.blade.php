@extends('admin.layouts.app')

@section('content')
    <link href="{{asset('js/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css"/>

    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><span class="font-weight-semibold">Edit groups permissions</span></h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>


        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="/admin" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
                    <a href="{{route('users_groups.index')}}" class="breadcrumb-item">groups permissions</a>
                    <span class="breadcrumb-item active">Edit</span>
                </div>

            </div>


        </div>
    </div>
    <!-- /page header -->


    <div class="content">

        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">groups permissions</h5>

            </div>

            <div class="card-body">
                <form class=" m-t-40 " method="post"
                      @if(isset($item))
                      action="{{route('users_groups.update',$item->id)}}"
                      @else
                      action="{{route('users_groups.store')}}"
                    @endif >
                    {{csrf_field()}}


                    @if(isset($item))
                        {{ method_field('PUT') }}
                        <input name="item_id" type="hidden" value="{{$item->id}}">
                    @endif
                    <div class="row">
                        <div class="col-6">

                            <div class="form-group">
                                <label class="control-label">Group name</label>
                                <input value="{{isset($item) ? $item->name : ''}}" required name="name"
                                       class="form-control" type="text">
                                <small class="form-control-feedback"></small>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Group permissions</label>
                                <select required class="form-control"
                                        name="permissions[]" id="permissions" multiple>
                                    @foreach($permissions as $permission)
                                        <option value="{{$permission->id}}"
                                                @if(isset($item) && $item->permissions->contains('id',$permission->id)) selected @endif>{{$permission->name}}</option>
                                    @endforeach
                                </select>
                                <small class="form-control-feedback"></small>
                            </div>

                            <div class="button-box m-t-20 m-b-20">
                                <a id="select-all" class="btn btn-danger" href="javascript:void(0)">Select all</a>
                                <a id="deselect-all" class="btn btn-info" href="javascript:void(0)">De-select all</a>

                                <a id="refresh" class="btn btn-warning" href="javascript:void(0)">Refresh</a>
                            </div>
                            <button class="btn btn-success mt-5" type="submit">Save</button>

                        </div>

                    </div>




                </form>
            </div>
        </div>





    </div>
@endsection
@section('js_assets')

    <script type="text/javascript"
            src="{{asset('js/multiselect/js/jquery.multi-select.js')}}"></script>
    <script>

        $('#permissions').multiSelect();

        $('#select-all').click(function () {
            $('#permissions').multiSelect('select_all');
            return false;
        });
        $('#deselect-all').click(function () {
            $('#permissions').multiSelect('deselect_all');
            return false;
        });
        $('#refresh').on('click', function () {
            $('#permissions').multiSelect('refresh');
            return false;
        });
    </script>

@endsection
