
@extends('auth')

@section('pageStyle')

    <style>

        .detail-card{
            height: 100%;
        }
        .product-name{
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .price-description .not-price{
            color: #999;
            position: relative;
            font-size: 13px;
        }
        .price-description .not-price::after{
            content: '';
            background: #999;
            position: absolute;
            width: 100%;
            height: 1px;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
        }
        .price-description .price{
            font-size: 20px;
            font-weight: bold;
            margin-left: 5px;
            line-height: 14px;
        }
        .price-description .offer-percent{
            font-size: 13px;
            margin-left: 5px;
            color: var(--green);
        }

        .customer-name{
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .customer-address{
            font-weight: 600;
            margin-bottom: 5px;
        }
        .customer-address .badge{
            background: var(--orange);
            color: #fff;
            font-weight: normal;
        }
        .address{
            font-style: italic;
            font-size: 13px;
        }

        .title{
            font-weight: 600;
            font-size: 22px;
            padding-bottom: 20px;
            position: relative;
        }
        .title::after{
            content: '';
            position: absolute;
            bottom: 15px;
            left: 0;
            width: 100%;
            height: 1px;
            background: #999;
        }
        .tabular{
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
            font-size: 14px;
        }
        .tabular span{
            font-weight: 600;
        }
        .total{
            margin-bottom: 0;
            padding-top: 20px;
            font-size: 16px;
            font-weight: 600;
            position: relative;
        }
        .total::before{
            content: '';
            position: absolute;
            top: 15px;
            left: 0;
            height: 1px;
            width: 100%;
            background: #999;
        }
        .statuses{
            display: flex;
            justify-content: space-between;
            align-items: start;
            position: relative;
        }
        .status{
            text-align: center;
            z-index: 1;
        }
        .status-title{
            font-weight: 600;
            font-size: 16px;
            background: #fff;
        }
        .status-status{
            font-size: 13px;
            padding-top: 6px;
            background: #fff;
            color: #999;
        }
        .status-status.danger{
            color: var(--danger);
        }
        .status-status.done{
            color: var(--success);
        }
        .status-status.warning{
            color: var(--warning);
        }
        /* .statuses::after{
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translateY(-50%);
            height: 3px;
            background: #ddd;
            z-index: 0;
        } */
        .status-action{
            text-align: center;
            margin-top: 20px;
        }

    </style>

@stop

@section('pageContent')

    <div class="container-fluid">

        <div class="card rounded">
            <div class="card-body">
                <div class="status-flow"></div>
                <div class="statuses">
                    <div class="status">
                        <div class="status-title">Order Placed</div>
                        <div class="status-status done">Placed by Customer</div>
                    </div>

                    @if($order['status'] == 2)

                        <!-- Cancelled Order -->
                        <div class="status">
                            <div class="status-title"> {{ $statuses[1]['status'] }} </div>
                            <div class="status-status success"> {{ $statuses[1]['status'] }} by Customer </div>
                        </div>
                        <!-- Cancelled Order -->

                    @elseif($order['status'] == 7)

                        <!-- Cancelled Order -->
                        <div class="status">
                            <div class="status-title"> {{ $statuses[6]['status'] }} </div>
                            <div class="status-status danger"> {{ $statuses[6]['status'] }} </div>
                        </div>
                        <!-- Cancelled Order -->
                    
                    @else
                        @for($i = 3; $i < 7; $i++)
                        
                            
                            <div class="status">
                                <div class="status-title"> {{ $statuses[$i - 1]['status'] }} </div>
                                <div class="status-status {{ $order['status'] == ($i - 1) || ($i == 3 && $order['status'] == 1)  ? 'warning' : '' }} {{ $order['status'] >= $i ? 'done' : '' }}"> {{ $order['status'] >= $i  ? $statuses[$i - 1]['status'] : ( $order['status'] == ($i - 1) || ($i == 3 && $order['status'] == 1)  ? 'Pending' : 'In Queue' ) }} </div>
                            </div>

                        @endfor
                    @endif

                </div>
                <div class="status-action">
                    @if($order['status'] == 1)
                        <button class="btn btn-success btn-sm rounded" onclick="nextStatus('Do you want to accept this order?')">Accept Order</button>
                        <button class="btn btn-danger btn-sm rounded" onclick="reject()">Reject Order</button>
                    @elseif($order['status'] < 6 && $order['status'] > 1)
                        <button class="btn btn-success btn-sm rounded" onclick="nextStatus()">{{ $statuses[$order['status']]['status'] }}</button>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-4">

                <div class="card detail-card">
                    <div class="card-body">
                        <div class="text-center mb-2">
                            <img src="{!! $order['image'] !!}" alt="Product image" width="50%" />
                        </div>

                        <div class="product-detail">
                            <small class="brand"> {!! $order['brand'] !!} </small>
                            <div class="product-name">{!! $order['productName'] !!}</div>
                            <div class="price-description">
                                <span class="not-price">{{ $order['offerEnable'] ? '₹'.$order['sellingPrice'] : '' }}</span>
                                <span class="price">₹{{ $order['offerEnable'] ? $order['offerPrice'] : $order['sellingPrice'] }}</span>
                                <span class="offer-percent">{{ $order['offerEnable'] ? $order['offerPercentage'].'% off' : '' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-4">

                <div class="card detail-card">
                    <div class="card-body">
                        <div class="customer-name"> {!! $order['name'] !!} (<a href="tel:+91{{ $order['mobileNo'] }}"> +91-{{ $order['mobileNo'] }} </a>) </div>
                        <div class="customer-address">Address - <div class="badge">{{ $order['type'] }}</div></div>
                        <div class="address">{{ $order['address1'] }} <br> {{ $order['address2']}} <br> {{ $order['city'] }} - {{ $order['pincode'] }} <br> {{ $order['state'] }}</div>
                    </div>
                </div>

            </div>

            <div class="col-md-4">
                <div class="card detail-card">
                    <div class="card-body">
                        <div class="title">Order Summary</div>
                        <div class="tabular">Price : <span>₹{{ $order['offerEnable'] ? $order['offerPrice'] : $order['sellingPrice'] }}</span></div>
                        <div class="tabular">Quantity : <span>{{ $order['quantity'] }}</span></div>
                        <div class="tabular total">Total : <span>₹{{ $order['offerEnable'] ? $order['offerPrice'] * $order['quantity'] : $order['sellingPrice'] * $order['quantity'] }}</span></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
@stop


@section('pageScript')

    <script>

        const url = `{{ url('order') }}`;
        const nextStatus = (title = "Update status of order?") => {
            swal(
                {
                    title: "Perform action",
                    text: title,
                    type: "warning",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, update!",
                    showLoaderOnConfirm: true
                },
                () => $.ajax({
                    type: 'POST',
                    url: `${url}/nextaction/{{ $order['orderNo'] }}`,
                    dataType: 'json',
                    cache : false,
                    processData: false,
                    success: (response, status, xhr) => {
                        swal({
                                title: response.text,
                                text: "",
                                type:  response.success ? "success" : "error",   
                            },
                            () => window.location.reload()
                        );
                    }
                })
            );
        };

        const reject = () => {
            swal(
                {
                    title: "Are you sure?",
                    text: "This order will be rejected!",
                    type: "warning",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, reject it!",
                    showLoaderOnConfirm: true
                },
                () => $.ajax({
                    type: 'POST',
                    url: `${url}/reject/{{ $order['orderNo'] }}`,
                    dataType: 'json',
                    cache : false,
                    processData: false,
                    success: (response, status, xhr) => {
                        swal({
                                title: response.text,
                                text: "",
                                type:  response.success ? "success" : "error",   
                            },
                            () => window.location.reload()
                        );
                    }
                })
            );
        };

    </script>

@stop
