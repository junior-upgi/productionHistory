@extends('layouts.masterpage')
@section('title', '缺點項目管理')
@section('content')
<div id="item">
    <p></p>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="pull-left">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" 
                    data-placement="top" title="新增" @click="add()" @mouseenter="tooltip($event)">
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
            </div>
            <div class="row">
                <div class="pull-right col-md-5">
                    <div class="input-group">
                        <input type="text" id="search_name" class="form-control input-sm" placeholder="缺點項目名稱" value="">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" 
                                data-placement="top" title="搜尋" @click="search()" @mouseenter="tooltip($event)">
                                <span class="glyphicon glyphicon-search"></span>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <p></p>
    <div v-show="items.length > 0" class="row">
        <div class="col-md-6 col-md-offset-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td></td>
                        <td>缺點項目名稱</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="i in items">
                        <td width="51">
                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" 
                                data-placement="top" title="編輯" @click="edit(i.id)" @mouseenter="tooltip($event)">
                                <span class="glyphicon glyphicon-edit"></span>
                            </button>
                        </td>
                        <td>@{{ i.name }}</td>
                        <td width="51">
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" 
                                data-placement="top" title="刪除" @click="del(i.id)" @mouseenter="tooltip($event)">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    @include('defect.add')
</div>
<script src="{{ url('js/defect/item.js?v=2') }}"></script>
@endsection