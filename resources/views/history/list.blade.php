@extends('layouts.masterpage')
@section('content')
<script src="{{ url('/js/history/list.js') }}"></script>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <h2>品質管制產品履歷表</h2>
        <div class="pull-left">
            <button class="btn btn-primary" onclick="add()">新增</button>
        </div>
        <form class="form-inline pull-right" action="" role="form">
            <div class="row form-group">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="請輸入瓶號">
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-2">
                    <select class="form-control" name="" id="">
                        <option value="">全部</option>
                        <option value="">1-1</option>
                        <option value="">1</option>
                        <option value="">2</option>
                        <option value="">3</option>
                        <option value="">5</option>
                        <option value="">6</option>
                        <option value="">7</option>
                        <option value="">8</option>
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
<p></p>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td style="width: 127px;"></td>
                    <td>瓶號</td>
                    <td>生產線</td>
                    <td>日期</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" 
                            data-placement="top" title="編輯" onclick="edit()">
                            <span class="glyphicon glyphicon-edit"></span>
                        </button>
                        <a href="{{ url('/nav/parameter.detail') }}" class="btn btn-default btn-sm" data-toggle="tooltip" 
                            data-placement="top" title="詳細內容">
                            <span class="glyphicon glyphicon-list-alt"></span>
                        </a>
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" 
                            data-placement="top" title="刪除" onclick="">
                            <span class="glyphicon glyphicon-trash"></span>
                        </button>
                    </td>
                    <td>14045</td>
                    <td>8</td>
                    <td>2016-12-01</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@include('history.add')
@endsection