@extends('admin.layouts.app')

@section('content')

    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><span class="font-weight-semibold">Users</span></h4>
            </div>
            <div class="header-elements">
                <a type="button" class="btn btn-primary" href="{{route('users.create')}}">Create</a>
            </div>

        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="/admin" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
                    <a href="{{route('users.index')}}" class="breadcrumb-item">Users</a>

                </div>

                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>


        </div>
    </div>
    <!-- /page header -->


    <div class="content">

        <!-- Basic table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table">
                    <thead>

                    <tr>

                        <th class="numeric">id</th>
                        <th class="">Name (AR)</th>
                        <th class="">Name (EN)</th>
                        <th class="">Email</th>
                        <th class="">Role</th>
                        <th class="">Control</th>

                    </tr>

                    </thead>

                    <tbody>


                    @foreach($users as $user)

                        <tr @if(session('id') === $user->id)class="bg-green" @endif>
                            <td>{{$user->id}}</td>
                            <td>{{$user->name_ar ?? $user->name}}  @if(session('id') === $user->id) | updated @endif</td>
                            <td>{{$user->name_en ?? $user->name}}</td>

                            <td>{{$user->email}}</td>

                            <td>{{isset($user->roles[0]) ? $user->roles[0]->name : ''}}</td>


                            <td>
                                <a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Take
                                    action</a>

                                <div class="dropdown-menu dropdown-menu-lg">
                                <a class="dropdown-item d-none" data-placement="top" title="Show"
                                   href="{{route('users.show',$user->id)}}"
                                  ><i class="icon-eye"></i>Show</a>

                                <a class="dropdown-item" data-placement="top" title="Delete" href="javascript:void(0)"
                                   onclick="delete_item('{{$user->id}}','{{$user->name}}')" data-toggle="modal"
                                   data-target="#delete_item_modal"><i class="icon-cross3"></i>Delete</a>

                                <a class="dropdown-item" data-toggle="tooltip" data-placement="top" title="Edit"
                                   href="{{route('users.edit',$user->id)}}"><i class="icon-pencil7"></i>Edit</a>

                                </div>



                            </td>

                        </tr>

                    @endforeach


                    </tbody>
                </table>
            </div>
        </div>
        <!-- /basic table -->
        {{----}}
        <div id="delete_item_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="delete_form" method="post" action="">
                        @csrf
                        {{ method_field('DELETE') }}
                        <input name="id" id="item_id" class="form-control" type="hidden">
                        <input name="_method" type="hidden" value="DELETE">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Delete User <span id="del_label_title"></span>
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <h4>Confirm Delete User</h4>
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



        <div id="LinkNotModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">

                        <h4 class="modal-title">Link Mobile Notifications</h4>
                    </div>
                    <div class="modal-body">
                        <p>Linking Url</p>
                        <p id="url_span"></p>
                        <input class="form-control" id="linking_url" value="">

                    </div>
                    <div class="modal-footer">
                        {{--<a class="btn btn-danger Delete-Action">Confirm</a>--}}
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
        </div>
        <!-- /.modal-dialog -->



    </div>
@endsection

@section('js_assets')
@endsection

@section('js_code')

    <script>

        function delete_item(id, title) {
            $('#item_id').val(id);
            var url = "{{url('admin/users')}}/" + id;
            $('#delete_form').attr('action', url);
            $('#grup_title').text(title);
            $('#del_label_title').html(title);
        }


        $('#LinkNotModal').on('show.bs.modal', function (event) {

            //linking_url
            var button = $(event.relatedTarget) // Button that triggered the modal
            var title = button.data('title') // Extract info from data-* attributes
            var hash = button.data('hash') // Extract info from data-* attributes
            var url = button.attr('href') // Extract info from data-* attributes
            var modal = $(this)
            modal.find('.modal-title').html('<b>' + title + '</b>')
            modal.find('.modal-footer .Delete-Action').attr('href', url)

            $('#linking_url').val('{{url('api/link_mobile')}}?user_id=' + hash);
            $('#url_span').text('{{url('api/link_mobile')}}?user_id=' + hash);

            console.log(hash);

        });

    </script>

@endsection

