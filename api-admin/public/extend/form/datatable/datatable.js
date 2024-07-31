$(document).ready(function () {
    // 新增行
    $('.js-add-new').click(function () {
        const table_id = $(this).data('table');
        const table = $('#table-' + table_id);
        const fields = $(this).data('fields');
        const arr = Object.keys(fields);
        let tr = '<tr>';

        table.find('tr.empty').remove();
        console.log(table.find('tr').length)
        const trlength = table.find('tr').length;
        if (arr.length > 0) {
            $.each(fields, function (i, e) {

                if (e.type === 'text') {
                    tr += '<td><input class="form-control" type="text" name="' + table_id + '[' + i + '][]" placeholder="请填写..."></td>';
                } else if (e.type == 'checkbox') {
                    tr += ' <td>'
                    $.each(e.options, function (key, value) {
                        tr += ' <label class="css-input css-checkbox css-checkbox-primary css-checkbox-sm css-checkbox-rounded">' +
                            ' <input type="checkbox" name="' + table_id + '[' + i + '][' + trlength + '][' + key + ']"><span></span>' + value + ' </label>'
                    });
                    tr += ' </td>'
                } else {
                    tr += '<td><select class="form-control" name="' + table_id + '[' + i + '][]"><option value="">请选择...</option>';
                    $.each(e.options, function (key, value) {
                        tr += '<option value="' + key + '">' + value + '</option>';
                    });
                    tr += '</select></td>';
                }
            });
            tr += '<td><button class="btn btn-sm btn-danger js-delete-line" type="button"><i class="fa fa-times"></i> 删除</button></td>';
        }

        tr += '</tr>';
        console.log(tr);
        table.find('tbody').append(tr);
    });

    // 删除数据
    $(".table").on("click", '.js-delete-line', function () {
        var tr = $(this).closest('tr');
        layer.confirm('确定要删除吗?', {icon: 3, title: '提示'}, function (index) {
            tr.remove();
            layer.close(index)
        });
    });
});