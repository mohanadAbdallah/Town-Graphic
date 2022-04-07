@extends('admin.layouts.app')

@section('content')

    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><span class="font-weight-semibold">Orders</span></h4>
            </div>
            <div class="header-elements">
                <a type="button" class="btn btn-primary" href="{{route('orders.create')}}">Create</a>
            </div>

        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="/admin" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
                    <a href="{{route('orders.index')}}" class="breadcrumb-item">Orders</a>

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
                <table class="table" style="width: max-content;">
                    <thead>
                    <tr>
                        <th class="" scope="col">Order</th>
                        <th class="" scope="col">User</th>
                        <th class="" scope="col">Agent</th>
                        <th class="" scope="col">Total amount</th>
                        <th class="" scope="col">Delivery amount</th>
                        <th class="" scope="col">Total with fees</th>
                        <th class="" scope="col">Delivery date</th>
                        <th class="" scope="col">Status</th>
                        <th class="" scope="col">Rate</th>
                        <th class="" scope="col" style="">Control</th>
                    </tr>
                    </thead>

                    <tbody>


                    @foreach($orders as $order)

                        <tr @if(session('id') === $order->id)class="bg-green" @endif>
                            <td class="">
                                <span style="color: #2bbf69">#{{$order->order_number ?? '--'}}</span>
                            </td>
                            <td class="">{{$order->appUser->name ?? '--'}}</td>
                            <td class="">{{$order->agent->name ?? '--'}}</td>
                            <td class="">{{$order->total_amount ?? '--'}}</td>
                            <td class="">{{$order->delivery_amount ?? '--'}}</td>
                            <td class="">{{$order->total_amount_with_fees }}</td>
                            <td class="">{{$order->order_delivery_date ?? '--' }}</td>
                            <td class="" style="color:{{$order->status_color}}">{{$order->status_name ?? '--'}}</td>
                            <td class="" style="">
                                    @for($i = 0;$i < $order->rating;$i++)
                                        <i class="icon-star-full2" style="color: orange;font-size: 22px"></i>
                                    @endfor
                                    @for($i = 0;$i < (5 - $order->rating);$i++)
                                        <i class="icon-star-empty3" style="color: #cdcdcd;font-size: 22px"></i>
                                    @endfor
                            </td>


                            <td>
                                <a href="#" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Take
                                    action</a>

                                <div class="dropdown-menu dropdown-menu-lg">
                                <a class="dropdown-item " data-placement="top" title="Show"
                                   href="{{route('orders.show',$order->id)}}"
                                ><i class="icon-eye"></i>Show</a>

                                <a class="dropdown-item {{ $order->status == 0 ? '' : 'd-none' }} "
                                   data-placement="top" title="make on processing"
                                   href="{{route('orders.processing',$order->id)}}"
                                ><i class="icon-menu"></i>Processing</a>
                                <a class="dropdown-item {{$order->status == 1 ? '' : 'd-none' }} "
                                   data-placement="top" title="Approve"
                                   href="{{route('orders.approve',$order->id)}}"
                                ><i class="icon-checkmark"></i>Approve</a>
                                <a orderId="" transactionId=""
                                   class="dropdown-item  {{ $order->status == 0 || $order->status == 1  ? '' : 'd-none' }} "
                                   data-placement="top" title="Cancel"
                                   href="{{route('orders.cancel',$order->id)}}"
                                ><i class="icon-cross3"></i>Cancel</a>
                                <a class="dropdown-item  {{$order->status == 0 ? '' : 'd-none' }} "
                                   data-placement="top" title="Delivery" href="javascript:void(0)"
                                   onclick="delivery_item('{{$order->id}}','{{$order->name}}')"
                                   data-toggle="modal"
                                   data-target="#delivery_item_modal"><i class="icon-cart5"></i>Delivery</a>
                                @hasanyrole('admin')
                                <a class="dropdown-item  {{$order->status == 0 ? '' : 'd-none' }} "
                                   data-placement="top" title="Redirect" href="javascript:void(0)"
                                   onclick="redirect_item('{{$order->id}}','{{$order->name}}')"
                                   data-toggle="modal"
                                   data-target="#redirect_item_modal"><i class="icon-flip-vertical4"></i>Redirect</a>
                                @endhasanyrole
                                </div>
                            </td>

                        </tr>

                    @endforeach

                    <tr>
                        <td colspan="10">
                            {{ $orders->links() }}
                        </td>
                    </tr>
                    </tbody>

                </table>
            </div>
        </div>
        <!-- /basic table -->
        {{----}}
        <div id="delivery_item_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="delivery_form" action="" method="post"
                          enctype="multipart/form-data">


                        {{csrf_field()}}

                        <input name="id" id="item_id" class="form-control" type="hidden">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Delivery Order <span
                                    id="del_label_title"></span>
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="control-label" for="name">Delivery amount</label>
                                <input type="number" class="form-control" id="delivery_amount" name="delivery_amount"
                                       value="">
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="name">Delivery date</label>
                                <input type="date" class="form-control" id="delivery_date" name="delivery_date"
                                       value="">
                            </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close
                            </button>
                            <button type="submit" class="btn btn-success waves-effect" id="delete_url">Save</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- /basic table -->
        {{----}}
        <div id="redirect_item_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="redirect_form" action="" method="post"
                          enctype="multipart/form-data">


                        {{csrf_field()}}

                        <input name="id" id="item_id" class="form-control" type="hidden">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Redirect Order <span
                                    id="del_label_title"></span>
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="control-label" for="name">Agent</label>
                                <select class="form-control" id="agent_id" name="agent_id">
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close
                            </button>
                            <button type="submit" class="btn btn-success waves-effect" id="delete_url">Save</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>


    </div>
@endsection

@section('js_assets')
@endsection

@section('js_code')

    <script>

        function delivery_item(id, title) {
            $('#item_id').val(id);
            var url = "{{url('admin/orders/delivery')}}/" + id;
            $('#delivery_form').attr('action', url);
        }

        function redirect_item(id, title) {
            $('#item_id').val(id);
            var url = "{{url('admin/orders/redirect')}}/" + id;
            $('#redirect_form').attr('action', url);
        }

        function delete_item(id, title) {
            $('#item_id').val(id);
            var url = "{{url('admin/advertisements')}}/" + id;
            $('#delete_form').attr('action', url);
            $('#grup_title').text(title);
            $('#del_label_title').html(title);
        }

        $(document).on('mousemove','table tr td',function () {
            if ($(this).index() <= 4){
                $(".table-responsive").scrollLeft(0);
            }else{
                $(".table-responsive").scrollLeft(10000);
            }
        });

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

