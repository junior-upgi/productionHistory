function add() {
    $('#modalTitle').html('新增生產條件資料');
    $('#btnSave').html('新增');
    $('#type').val('add');
    $('#id').val('');
    $('#name').val('');
    $('#addModal').modal('show');
}

function edit() {
    //var json = '';
    $('#modalTitle').html('編輯生產條件資料');
    $('#btnSave').html('更新');
    $('#type').val('edit');
    //$('#id').val(json['ID']);
    //$('#name').val(json['name']);
    $('#addModal').modal('show');
}