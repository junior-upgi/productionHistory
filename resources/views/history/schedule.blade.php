@extends('layouts.master_page')
@section('title', '產個履歷表-生產排程清單')
@section('content')
<script src="{{ url('/js/history/schedule.js?v=14') }}"></script>
<div class="row">
    <div class="col-md-12">
        <h2>產品履歷表-生產排程清單</h2>
        <div class="pull-left col-xs-6 row">
            <a class="btn btn-default" href="{{ url('/History/HistoryList') }}" style="margin-bottom: 10px;">返回履歷表清單</a>
            <button class="btn btn-primary" onclick="massProduction()" style="margin-bottom: 10px;">新增量產履歷表</button>
            <button class="btn btn-primary" onclick="testModel()" style="margin-bottom: 10px;">新增試模履歷表</button>
        </div>
        <form class="form-inline pull-right" action="{{ url('/History/ScheduleList') }}" role="form" style="margin-bottom: 10px;">
            <div class="row form-group">
                <div class="col-md-3">
                    <input type="text" name="snm" class="form-control" placeholder="請輸入瓶號" value="{{ $snm }}">
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-3">
                    <select class="form-control" name="glassProdLineID">
                        <option value="" {{ $glassProdLineID == '' ? 'selected': '' }}>全部產線</option>
                        <option value="L1-1" {{ $glassProdLineID == 'L1-1' ? 'selected': '' }}>L1-1</option>
                        <option value="L1" {{ $glassProdLineID == 'L1' ? 'selected': '' }}>L1</option>
                        <option value="L2" {{ $glassProdLineID == 'L2' ? 'selected': '' }}>L2</option>
                        <option value="L3" {{ $glassProdLineID == 'L3' ? 'selected': '' }}>L3</option>
                        <option value="L5" {{ $glassProdLineID == 'L5' ? 'selected': '' }}>L5</option>
                        <option value="L6" {{ $glassProdLineID == 'L6' ? 'selected': '' }}>L6</option>
                        <option value="L7" {{ $glassProdLineID == 'L7' ? 'selected': '' }}>L7</option>
                        <option value="L8" {{ $glassProdLineID == 'L8' ? 'selected': '' }}>L8</option>
                    </select>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-3">
                    <input type="text" name="schedate" readonly class="form-control date form_datetime" placeholder="請輸入生產日期" value="{{ $schedate }}">
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
                    <td style="width: 45px;">類型</td>
                    <td>生產日期</td>
                    <td>產品名稱</td>
                    <td>線別</td>
                    <td>預計生產量</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($list as $item)
                    <tr>
                        <td>
                            <button class="btn btn-primary" onclick="history('{{ $item->prd_no }}', '{{ $item->glassProdLineID }}', '{{ $item->schedate }}')">填寫履歷表</button>
                        </td>
                        <td>
                            @if($item['sampling'] == 0)
                                <span>量產</span>
                            @else
                                <span>試模</span>
                            @endif
                        </td>
                        <td>{{ $item->schedate }}</td>
                        <td>{{ $item->snm }}</td>
                        <td>{{ $item->glassProdLineID }}</td>
                        <td class="text-right">{{ number_format($item->orderQty) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p>
            {{ $list->setPath(url('/History/ScheduleList?snm=' . $snm . '&glassProdLineID=' . $glassProdLineID . '&schedate=' . $schedate)) }}
        </p>
    </div>
</div>
@include('history.add')
@endsection