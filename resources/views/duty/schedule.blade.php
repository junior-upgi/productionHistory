@extends('layouts.masterpage')
@section('content')
<script src="{{ url('/js/duty/schedule.js?x=2') }}"></script>
<div class="row">
    <div class="col-md-12">
        <h2>生產排程清單</h2>
        <div class="pull-left col-md-6 row">
            <a class="btn btn-default" href="{{ url('/Duty/DutyList') }}">返回值班表清單</a>
        </div>
        <form class="form-inline pull-right" action="{{ url('/Duty/ScheduleList') }}" role="form">
            <div class="row form-group">
                <div class="col-md-3">
                    <input type="text" name="snm" class="form-control" placeholder="請輸入瓶號" value="{{ $snm }}">
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-3">
                    <select class="form-control" name="glassProdLineID">
                        <option value="" {{ $glassProdLineID == '' ? 'selected': '' }}>全部產線</option>
                        <option value="1-1" {{ $glassProdLineID == '1-1' ? 'selected': '' }}>1-1</option>
                        <option value="01" {{ $glassProdLineID == '01' ? 'selected': '' }}>01</option>
                        <option value="02" {{ $glassProdLineID == '02' ? 'selected': '' }}>02</option>
                        <option value="03" {{ $glassProdLineID == '03' ? 'selected': '' }}>03</option>
                        <option value="05" {{ $glassProdLineID == '05' ? 'selected': '' }}>05</option>
                        <option value="06" {{ $glassProdLineID == '06' ? 'selected': '' }}>06</option>
                        <option value="07" {{ $glassProdLineID == '07' ? 'selected': '' }}>07</option>
                        <option value="08" {{ $glassProdLineID == '08' ? 'selected': '' }}>08</option>
                    </select>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-3">
                    <input type="text" name="schedate" readonly class="form-control date form_datetime" value="{{ $schedate }}">
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
<p></p>
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td style="width: 127px;"></td>
                    <td>排程日期</td>
                    <td>產品名稱</td>
                    <td>線別</td>
                    <td>預計排程量</td>
            </thead>
            <tbody>
                @foreach($list as $item)
                    <tr>
                        <td>
                            <button class="btn btn-primary" onclick="duty('{{ $item->prd_no }}', '{{ $item->glassProdLineID }}', '{{ $item->schedate }}')">填寫值班表</button>
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
            {{ $list->setPath('/Duty/ScheduleList?snm=' . $snm . '&glassProdLineID=' . $glassProdLineID . '&schedate=' . $schedate) }}
        </p>
    </div>
</div>
@include('duty.add')
@endsection