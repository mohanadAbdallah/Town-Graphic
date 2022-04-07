@extends('admin.layouts.app')

@section('content')

    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><span class="font-weight-semibold">Categories</span></h4>
            </div>
            <div class="header-elements">
                <a type="button" class="btn btn-primary" href="{{route('categories.create')}}">Create</a>
            </div>

        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="/admin" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
                    <a href="{{route('categories.index')}}" class="breadcrumb-item">Categories</a>

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
                        <th class="">Title (AR)</th>
                        @hasanyrole('agent')
                        <th class="">Title (EN)</th>
                        @endhasanyrole
                        <th class="">Image</th>
                        @hasanyrole('admin')
                        <th class="">Agent</th>
                        @endhasanyrole
                        <th class="" style="width: 36%">Control</th>

                    </tr>

                    </thead>

                    <tbody>


                    @foreach($categories as $category)

                        <tr style="background-color:#f5f5f5 @if(session('id') === $category->id and session('type') == 'category') color:gray @endif"
                            @if(session('id') === $category->id and session('type') == 'category') class="bg-green" @endif>
                            <td>{{$loop->index + 1}}</td>
                            <td>{{$category->title_ar}}  @if(session('id') === $category->id) | updated @endif</td>
                            @hasanyrole('agent')
                            <td>{{$category->title_en}}</td>
                            @endhasanyrole
                            <td>
                                @if($category->image)
                                    <img src="{{FileStorage::getUrl($category->image)}}" alt="img"
                                         style="width: 40px;border-radius: 5px;">
                                @else
                                    --
                                @endif
                            </td>
                            @hasanyrole('admin')
                            <td>{{$category->user->name_ar}}</td>
                            @endhasanyrole
                            <td>
                                <a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Take
                                    action</a>

                                <div class="dropdown-menu dropdown-menu-lg">
                                    <a class="dropdown-item " data-placement="top" title="Show"
                                       href="{{route('categories.show',$category->id)}}"
                                    ><i class="icon-eye"></i>Show</a>

                                    <a class="dropdown-item" data-placement="top" title="Delete"
                                       href="javascript:void(0)"
                                       onclick="delete_item_categories('{{$category->id}}','{{$category->name}}')"
                                       data-toggle="modal"
                                       data-target="#delete_item_modal"><i class="icon-cross3"></i>Delete</a>

                                    <a class="dropdown-item" data-toggle="tooltip" data-placement="top" title="Edit"
                                       href="{{route('categories.edit',$category->id)}}"><i class="icon-pencil7"></i>Edit</a>

                                    <a class="dropdown-item" data-toggle="tooltip" data-placement="top" title="Edit"
                                       href="{{route('subcategories.create',$category->id)}}"><i class="icon-plus3"></i>New sub
                                        Category</a>
                                    @if($category->status == 0)
                                        <a class="dropdown-item" href="{{route('categories.activate',$category->id)}}"
                                           data-toggle="tooltip" data-placement="top" title="Active"><i
                                                class="icon-megaphone"></i>Active</a>
                                    @else
                                        <a class="dropdown-item" href="{{route('categories.activate',$category->id)}}"
                                           data-toggle="tooltip" data-placement="top" title="Disable"><i
                                                class="icon-eye-blocked"></i>Disable</a>
                                    @endif
                                </div>
                            </td>

                        </tr>
                        @foreach($category->subCategory as $subCategory)
                            <tr @if(session('id') === $subCategory->id  and session('type') == 'subcategory')class="bg-green" @endif>
                                <td style="padding-left: 50px;">{{$loop->index + 1}}</td>
                                <td style="padding-left: 50px;">{{$subCategory->title_ar}}  @if(session('id') === $subCategory->id)
                                        | updated @endif</td>
                                @hasanyrole('agent')
                                <td style="padding-left: 50px;">{{$subCategory->title_en}}</td>
                                @endhasanyrole
                                <td style="padding-left: 50px;">
                                    @if($subCategory->image)
                                        <img src="{{FileStorage::getUrl($subCategory->image)}}" alt="img"
                                             style="width: 40px;border-radius: 5px;">
                                    @else
                                        --
                                    @endif
                                </td>
                                @hasanyrole('admin')
                                <td>{{$category->user->name_ar}}</td>
                                @endhasanyrole
                                <td style="padding-left: 50px;">
                                    <a href="#" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">Take
                                        action</a>

                                    <div class="dropdown-menu dropdown-menu-lg">
                                        <a class="dropdown-item " data-placement="top" title="Show"
                                           href="{{route('subcategories.show',$subCategory->id)}}"
                                        ><i
                                                class="icon-eye"></i>Show</a>

                                        <a class="dropdown-item" data-placement="top" title="Delete"
                                           href="javascript:void(0)"
                                           onclick="delete_item_sub_categories('{{$subCategory->id}}','{{$subCategory->name}}')"
                                           data-toggle="modal"
                                           data-target="#delete_item_modal"><i
                                                class="icon-cross3"></i>Delete</a>

                                        <a class="dropdown-item" data-toggle="tooltip" data-placement="top"
                                           title="Edit"
                                           href="{{route('subcategories.edit',$subCategory->id)}}"><i
                                                class="icon-pencil7"></i>Edit</a>
                                        @if($subCategory->status == 0)
                                            <a class="dropdown-item"
                                               href="{{route('subcategories.activate',$subCategory->id)}}"
                                               data-toggle="tooltip" data-placement="top" title="Active"><i
                                                    class="icon-megaphone"></i>Active</a>
                                        @else
                                            <a class="dropdown-item"
                                               href="{{route('subcategories.activate',$subCategory->id)}}"
                                               data-toggle="tooltip" data-placement="top" title="Disable"><i
                                                    class="icon-eye-blocked"></i>Disable</a>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @endforeach
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
                            <h4 class="modal-title" id="myModalLabel">Delete Item <span id="del_label_title"></span>
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <h4>Confirm Delete Item</h4>
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


        <!-- /.modal-dialog -->


    </div>
@endsection

@section('js_assets')
@endsection

@section('js_code')

    <script>

        function delete_item_categories(id, title) {
            $('#item_id').val(id);
            var url = "{{url('admin/categories')}}/" + id;
            $('#delete_form').attr('action', url);
            $('#grup_title').text(title);
            $('#del_label_title').html(title);
        }

        function delete_item_sub_categories(id, title) {
            $('#item_id').val(id);
            var url = "{{url('admin/subcategories')}}/" + id;
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

