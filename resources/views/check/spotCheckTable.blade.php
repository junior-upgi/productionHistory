<div class="table-responsive">
    <table class="table table-bordered table-condensed">
        <thead>
        <tr>
            <td rowspan="2"></td>
            <td rowspan="2"></td>
            <td rowspan="2" width="80">生產日期</td>
            <td v-for="item in spotCheckItems"
                v-if="spotCheckItemsCount[item.itemID] != 0"
                v-bind:colspan="spotCheckItemsCount[item.itemID]">@{{ item.itemName }}</td>
        </tr>
        <tr>
            <td v-for="defect in spotCheckDefects">@{{ defect.defectName }}</td>
        </tr>
        </thead>
        <tbody>
        <tr v-for="p in productionData" v-if="p.spotCheck == 1">
            <td width="56"><button class="btn btn-sm btn-default" v-on:click="editSpotCheckDefectShow(p.id)">編輯</button></td>
            <td width="56"><button class="btn btn-sm btn-danger" v-on:click="del(p.id)">刪除</button></td>
            <td>@{{ getDate(p.prodDate) }}</td>
            <td v-for="d in defectList" v-if="d.productionDataID == p.id">@{{ d.value }}</td>
        </tr>
        <tr v-if="spotCheckCount > 0">
            <td colspan="3" class="text-right">缺點平均值</td>
            <td v-for="a in avgSpotCheckDefect">@{{ a }}</td>
        </tr>
        </tbody>
    </table>
</div>