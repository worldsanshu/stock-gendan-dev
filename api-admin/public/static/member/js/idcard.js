$(function () {
    var card_pic_hidden = $("input[name='card_pic_hidden']").val();

    if (card_pic_hidden != '') {
        $('#form_group_name').after('<div class="form-group col-md-12 col-xs-12 " id="form_group_card_pic"><label class="col-xs-12" for="id_card">身份证正面照</label><div class="col-sm-12"><div id="WU_FILE_0" class="file-item js-gallery thumbnail"><a style="display: inline;" href="' + card_pic_hidden + '" data-toggle="tooltip"title="点击查看大图"target="_blank"><img style="width:200px;" src="' + card_pic_hidden + '"></a></div></div></div>');
    }
    var card_pic_back_hidden = $("input[name='card_pic_back_hidden']").val();
    if (card_pic_back_hidden != '') {
        $('#form_group_card_pic').after('<div class="form-group col-md-12 col-xs-12 " id="form_group_card_pic_back"><label class="col-xs-12" for="id_card">身份证背面照</label><div class="col-sm-12"><div id="WU_FILE_0" class="file-item js-gallery thumbnail"><a style="display: inline;" href="' + card_pic_back_hidden + '" data-toggle="tooltip"title="点击查看大图"target="_blank"><img style="width:200px;" src="' + card_pic_back_hidden + '"></a></div></div></div>');
    }

    var passport_pic_text = $("input[name='passport_pic_text']").val();
    if (passport_pic_text != '') {
        $('#form_group_card_pic_back').after('<div class="form-group col-md-12 col-xs-12 " id="form_group_card_pic_hand"><label class="col-xs-12" for="id_card">护照</label><div class="col-sm-12"><div id="WU_FILE_0" class="file-item js-gallery thumbnail"><a style="display: inline;" href="' + passport_pic_text + '" data-toggle="tooltip"title="点击查看大图"target="_blank"><img style="width:200px;" src="' + passport_pic_text + '"></a></div></div></div>');
    }


})