@extends('admin.layouts.app')

@section('content')

    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><span class="font-weight-semibold">User Group Permissions</span></h4>
            </div>
            <div class="header-elements">
                <a type="button" class="btn btn-primary" href="{{route('users_groups_permissions.create')}}">Create</a>
            </div>

        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="/admin" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
                    <a href="{{route('users_groups_permissions.index')}}" class="breadcrumb-item">User Group Permissions</a>

                </div>

                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>


        </div>
    </div>
    <!-- /page header -->


    <div class="content">

        <!-- Basic table -->
        <div class="card">
            @if(session()->has('success'))
                <div class="alert alert-success">
                    Updated Successfully
                </div>
            @endif


            <div class="table-responsive">
                <table class="table color-bordered-table info-table  info-bordered-table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th> Permission name</th>
                        <th> Action</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>

                            <td>{{$item->id}}</td>
                            <td>{{$item->name}}</td>


                            <td class="text-nowrap">
                                <a  class="btn btn-sm btn-success" href="{{route('users_groups_permissions.edit',$item->id)}}" data-toggle="tooltip"
                                    data-original-title="Edit">
                                    Edit </a>
                                <a  class="btn btn-sm btn-danger" href="javascript:void(0)" data-toggle="modal"
                                    data-target="#delete_item_modal" data-original-title="delete"
                                    onclick="delete_item('{{$item->id}}','{{$item->name}}')">
                                    Delete  </a>
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /basic table -->
        <!-- sample modal content -->
        <div id="delete_item_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">a
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="delete_form" method="post" action="{{route('users_groups_permissions.destroy',1)}}">
                        @csrf
                        {{ method_field('DELETE') }}
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Delete Permission</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <h4>Confirm Delete Permission :</h4>
                            <p id="grup_title"></p>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger waves-effect" id="delete_url">Delete</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

    </div>
@endsection

@section('js_assets')
@endsection


@section('js_code')


    <script>

        function delete_item(id, title) {
            var url = $('#delete_url').attr('href');
            console.log(id);
            $('#item_id').val(id);
            var url="{{url('admin/users_groups_permissions')}}/"+id;

            $('#delete_form').attr('action',url);

            //document.getElementById("delete_url").href = url + '/delete_user/' + id;

        }
    </script>

@endsection
