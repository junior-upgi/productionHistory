@extends('layouts.masterpage')
@section('content')
<script src="{{ url('/js/history/schedule.js?v=1') }}"></script>
<div class="row">
    <div class="col-md-12">
        <h2>生產排程清單</h2>
        <div class="pull-left col-xs-6 row">
            <a class="btn btn-default" href="{{ url('/History/HistoryList') }}" style="margin-bottom: 10px;">返回履歷表清單</a>
            <button class="btn btn-primary" onclick="testModel()" style="margin-bottom: 10px;">填寫試模履歷表</button>
        </div>
        <form class="form-inline pull-right" action="{{ url('/History/ScheduleList') }}" role="form" style="margin-bottom: 10px;">
            <div class="row form-group">
                <div class="col-md-3">
                    <input type="text" name="pname" class="form-control" placeholder="請輸入瓶號" value="{{ $pname }}">
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-3">
                    <select class="form-control" name="machno">
                        <option value="" {{ $machno == '' ? 'selected': '' }}>全部產線</option>
                        <option value="1-1" {{ $machno == '1-1' ? 'selected': '' }}>1-1</option>
                        <option value="01" {{ $machno == '01' ? 'selected': '' }}>01</option>
                        <option value="02" {{ $machno == '02' ? 'selected': '' }}>02</option>
                        <option value="03" {{ $machno == '03' ? 'selected': '' }}>03</option>
                        <option value="05" {{ $machno == '05' ? 'selected': '' }}>05</option>
                        <option value="06" {{ $machno == '06' ? 'selected': '' }}>06</option>
                        <option value="07" {{ $machno == '07' ? 'selected': '' }}>07</option>
                        <option value="08" {{ $machno == '08' ? 'selected': '' }}>08</option>
                    </select>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-2">
                    <button class="btn btn-default">搜尋</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td style="width: 127px;"></td>
                    <td>排程日期</td>
                    <td>產品名稱</td>
                    <td>預計排程量</td>
                    <td>排程量</td>
                    <td>線別</td>
                    <td>機速</td>
                    <td>重量</td>
                    <td>引出量</td>
                </tr>
            </thead>
            <tbody>
                @foreach($list as $item)
                    <tr>
                        <td>
                            <button class="btn btn-primary" onclick="history('{{ $item->mk_no }}')">填寫履歷表</button>
                        </td>
                        <td>{{ $item->schedate }}</td>
                        <td>{{ $item->NAME }}</td>
                        <td class="text-right">{{ number_format($item->scheqty) }}</td>
                        <td class="text-right">{{ number_format($item->allscheqty) }}</td>
                        <td>{{ $item->machno }}</td>
                        <td>{{ (int) $item->speed }}</td>
                        <td>{{ (float) $item->weight }}</td>
                        <td>{{ (int) $item->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p>
            {{ $list->setPath('/History/ScheduleList?pname=' . $pname . '&machno=' . $machno) }}
        </p>
    </div>
</div>
@include('history.add')
@endsection