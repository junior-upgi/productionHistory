@extends('layouts.masterpage')
@section('content')
    <script src="{{url('/')}}/js/order/orderSearch.js?x=1"></script>
    @inject('web', 'App\Presenters\WebBasePresenter')
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
                        <td >品名</td>
                        <td width="40">單位</td>
                        <td width="80">工序</td>
                        <td width="90">開始時間</td>
                        <td width="100">產線</td>
                        <td width="90">結束時間</td>
                        <td width="82"></td>
                        <td width="82"></td>
                        <td width="82"></td>
                        <td width="82"></td>
                        <td width="82"></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order as $o)
                        <tr>
                            <td>{{ $o->OS_NO }}</td>
                            <td>{{ $o->ITM }}</td>
                            @if (isset($o->UNIT))
                                <td>{{ $o->COMB_ITEM_NAME }}</td>
                            @else
                                <td><a href="{{ $web->getFormLink($o, 0) }}" target="_blank">{{ $o->COMB_ITEM_NAME }}</a></td>
                            @endif
                            <td>{{ $o->UNIT }}</td>
                            <td>{{ $o->PRC }}</td>
                            <td>{{ $web->getDate($o->SDATE) }}</td>
                            <td>{{ $o->PL }}</td>
                            <td>{{ $web->getDate($o->EDATE) }}</td>
                            @if (isset($o->UNIT))
                                <td><a href="{{ $web->getFormLink($o, 1) }}" target="_blank" class="btn btn-default btn-sm">設備材料</a></td>
                                <td><a href="{{ $web->getFormLink($o, 2) }}" target="_blank" class="btn btn-default btn-sm">製程條件</a></td>
                                <td><a href="{{ $web->getFormLink($o, 3) }}" target="_blank" class="btn btn-default btn-sm">管制要求</a></td>
                                <td><a href="{{ $web->getFormLink($o, 4) }}" target="_blank" class="btn btn-default btn-sm">問題缺點</a></td>
                                <td><a href="{{ $web->getFormLink($o, 5) }}" target="_blank" class="btn btn-default btn-sm">生產狀況</a></td>
                            @else
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection