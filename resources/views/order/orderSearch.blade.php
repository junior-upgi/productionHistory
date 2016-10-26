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
                        <td width="130">顧客</td>
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
                    </tr>
                </thead>
                <tbody>
                    @foreach($order as $o)
                        <tr>
                            <td>{{ $o->OS_NO }}</td>
                            <td>{{ $o->ITM }}</td>
                            <td>{{ $o->SNM }}</td>
                            @if (isset($o->department))
                                <td>
                                    <small>{{ $o->COMB_ITEM_NAME }}</small>
                                    {!! $web->getPic($o->image) !!}
                                    {!! $web->getPicAjax($o) !!}
                                </td>
                            @else
                                <td><small><a href="{{ $web->getFormLink($o, 'history', 'all') }}" target="_blank">{{ $o->COMB_ITEM_NAME }}</a></small></td>
                            @endif
                            <td>{{ $o->department }}</td>
                            <td>{{ $o->process }}</td>
                            <td>{{ $o->startDate }}</td>
                            <td>{{ $o->productionLine }}</td>
                            <td>{{ $o->endDate }}</td>
                            @if (isset($o->department))
                                <td><a href="{{ $web->getFormLink($o, 'setup', $o->department) }}" target="_blank" class="btn btn-default btn-sm">裝備設定</a></td>
                                <td><a href="{{ $web->getFormLink($o, 'parameter', $o->department) }}" target="_blank" class="btn btn-default btn-sm">生產條件</a></td>
                                <td><a href="{{ $web->getFormLink($o, 'issue', $o->department) }}" target="_blank" class="btn btn-default btn-sm">狀況記錄</a></td>
                                <td><a href="{{ $web->getFormLink($o, 'output', $o->department) }}" target="_blank" class="btn btn-default btn-sm">產出記錄</a></td>
                            @else
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
@include('order.picModal')
@endsection