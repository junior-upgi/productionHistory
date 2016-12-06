@extends('layouts.masterpage')
@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <a class="btn btn-default" href="{{ url('/nav/parameter.list') }}" style="margin-top: 10px;">返回清單</a>
    </div>
    <div class="col-md-8 col-md-offset-2 text-center">
        <h2>統義玻璃工業股份有限公司</h2>
    </div>
    <div class="col-md-8 col-md-offset-2 text-center">
        <h3>製造課生產條件記錄表</h3>
    </div>
    <div class="col-md-2 col-md-offset-2">瓶號：14045</div>
    <div class="col-md-2">重量：152 g</div>
    <div class="col-md-2">生產線：8</div>
    <div class="col-md-2">日期：2016-12-01</div>
    <div class="col-md-8 col-md-offset-2"  style="margin-top: 5px;">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td class="col-xs-6 col-md-6 text-center">機器設備</td>
                    <td class="col-xs-6 col-md-6 text-center">供給器</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>初模抱具：{{ null }}</td>
                    <td>型式：</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection