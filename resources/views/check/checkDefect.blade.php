<div role="tabpanel" style="margin-top: 15px;">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#productionDefect" aria-controls="production" role="tab" data-toggle="tab">生產缺點</a>
        </li>
        <li role="presentation">
            <a href="#spotCheck" aria-controls="spotCheck" role="tab" data-toggle="tab">抽驗品質</a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content" id="productionInfo">
        <div role="tabpanel" class="tab-pane active" id="productionDefect">
            <div style="margin-top: 10px; margin-bottom: 10px;">
                <button type="button" class="btn btn-primary" v-on:click="addProductionDefectShow()">新增生產缺點資料</button>
            </div>
            <div v-if="productionDataCount > 0">
                @include('check.productionDefectTable')
            </div>
            <div v-else>
                <span>目前尚無資料</span>
            </div>
            @include('check.addCheckDefect')
            @include('check.editCheckDefect')
        </div>
        <div role="tabpanel" class="tab-pane" id="spotCheck">
            <div style="margin-top: 10px; margin-bottom: 10px;">
                <button type="button" class="btn btn-primary" v-on:click="addSpotCheckDefectShow()">新增抽驗缺點資料</button>
            </div>
            <div v-if="spotCheckCount > 0">
                @include('check.spotCheckTable')
            </div>
            <div v-else>
                <span>目前尚無資料</span>
            </div>
            @include('check.addSpotCheckDefect')
            @include('check.editSpotCheckDefect')
        </div>
    </div>
</div>
<script src="{{ url('/js/check/checkDefect.js?v=11') }}"></script>
