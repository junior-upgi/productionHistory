<div class="modal fade" id="addModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="modalTitle">@{{ formSet.title }}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
                    <input type="hidden" name="type" v-model="dataSet.type">
                    <input type="hidden" name="id" v-model="dataSet.id">
                    <form class="form-inline" role="form">
                        <div class="form-group col-md-12">
                            <label for="name" class="control-label">缺點樣板名稱</label>
                            <input type="text" class="form-control" name="name" v-model="dataSet.name" required>
                        </div>
                    </form>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-md-5" style="height: 400px;overflow: auto;">
                        <label class="control-label">所有缺點項目</label>
                        <ul class="list-group">
                            <li class="list-group-item" v-for="si in setItem">
                                <label class="">
                                    <input type="checkbox" v-bind:id="si.id" v-bind:value="si.id" v-model="itemList">
                                    <span>@{{ si.name }}</span>
                                </label>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-1 text-center">
                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" 
                            style="margin-top: 100px;margin-left: 4px;"
                            data-placement="top" title="加入" @click="selectItem()" @mouseenter="tooltip($event)">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                        </button>
                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" style="margin-top: 50px;"
                            data-placement="top" title="移除" @click="removeItem()" @mouseenter="tooltip($event)">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                        </button>
                    </div>
                    <div class="col-md-6" style="height: 400px;overflow: auto;">
                        <label class="control-label">選擇的缺點項目</label>
                        <ul class="list-group" id="selectSort">
                            <li class="list-group-item" v-for="(ss, index) in setSelect">
                                <label class="">
                                    <input type="checkbox" v-bind:id="ss.id" v-bind:value="ss.id" v-model="selectList">
                                    <span>@{{ index }} .</span>
                                    <span>@{{ ss.name }}</span>
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
                    
                    <!--
                    <div class="col-md-5" style="margin-left: 35px;">
                        所有缺點項目
                        <select class="form-control" v-model="itemList" style="height: 400px;" multiple>
                            <option v-for="si in setItem" v-bind:value="si.id">@{{ si.name }}</option>
                        </select>
                    </div>
                    <div class="col-md-1 text-center">
                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" style="margin-top: 100px;margin-left: 4px;"
                            data-placement="top" title="加入" @click="selectItem()" @mouseenter="tooltip($event)">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                        </button>
                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" style="margin-top: 50px;"
                            data-placement="top" title="移除" @click="removeItem()" @mouseenter="tooltip($event)">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                        </button>
                    </div>
                    <div class="col-md-5">
                        選擇的缺點項目
                        <select class="form-control" v-model="selectList" style="height: 400px;" multiple>
                            <option v-for="ss in setSelect" v-bind:value="ss.id">@{{ ss.name }}</option>
                        </select>
                    </div>
                    -->
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
                <button type="button" class="btn btn-primary" data-loading-text="資料送出中..." 
                    autocomplete="off" id="btnSave" @click="save()">
                    @{{ formSet.btn }}
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->