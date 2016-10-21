@extends('layouts.masterpage')
@section('content')
    <script src="{{url('/')}}/js/order/orderSearch.js?x=1"></script>
        <form id="orderSearchForm" class="form-horizontal col-md-12" action="{{url('/')}}/order" method="POST">
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
            <div class="col-md-4">
                <input type="text" class="form-control" id="searchContent" name="searchContent" placeholder="請輸入查詢內容" value="{{ $search }}" />
            </div>
            <button type="submit" class="btn btn-primary">查詢</button>
        </form>
    @if(isset($order))
        <div class="table-responsive col-md-12" style="margin-top: 20px;">
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <td width="130">訂單編號</td>
                        <td width="40">項次</td>
                        <td width="200">品名</td>
                        <td>單位</td>
                        <td>工序</td>
                        <td>開始時間</td>
                        <td>產線</td>
                        <td>結束時間</td>
                        <td width="82">設備材料</td>
                        <td width="82">製程條件</td>
                        <td width="82">管制要求</td>
                        <td width="82">問題缺點</td>
                        <td width="82">生產狀況</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order as $o)
                        <tr>
                            <td>{{ $o->OS_NO }}</td>
                            <td>{{ $o->ITM }}</td>
                            @php
                                $oid = $o->OS_NO;
                                $itm = $o->ITM;
                                $url = "https://docs.google.com/forms/d/e/1FAIpQLSehNS4idn8yHXa2IPNzu5kNwNGE2aJbvHoTw3mS4OcpaxB99w/viewform?entry.1932040804=$oid&entry.1999602853=$itm&entry.1336051967&entry.1089230296&entry.1706891687";
                            @endphp
                            <td><a href="{{ $url }}" target="_blank">{{ $o->COMB_ITEM_NAME }}</a></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><a href="#" class="btn btn-default btn-sm">設備材料</a></td>
                            <td><a href="#" class="btn btn-default btn-sm">製程條件</a></td>
                            <td><a href="#" class="btn btn-default btn-sm">管制要求</a></td>
                            <td><a href="#" class="btn btn-default btn-sm">問題缺點</a></td>
                            <td><a href="#" class="btn btn-default btn-sm">生產狀況</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection