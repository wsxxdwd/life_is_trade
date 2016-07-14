$(document).ready(function() {
    var snackbarContainer = document.querySelector('#toast');
    $('#publishBtn').click(function(e) {
        e.preventDefault();
        var dataError = false;

        var poster = $('#poster').val();
        if(poster == '') {
            $('#poster').parent().addClass('is-invalid');
            dataError = true;
        }

        var type = $('[name="type"]').val();

        var title = $('#title').val();
        if(title == '') {
            $('#title').parent().addClass('is-invalid');
            dataError = true;
        }

        var staff = $('#staff').val();
        if(staff == '' ||
            staff.length > 255) {
            $('#staff').parent().addClass('is-invalid');
            dataError = true;
        }

        var quantity = $('#quantity').val();
        if(quantity == '' ||
            quantity > 9999 ||
            qiamtity < 0) {
            $('#quantity').parent().addClass('is-invalid');
            dataError = true;
        }

        var quality = $('#quality').val();
        if(quality == '' ||
            quality > 100 ||
            quality < 0) {
            $('#quality').parent().addClass('is-invalid');
            dataError = true;
        }

        var price = $('#quality').val();
        if(price == '') {
            $('#price').parent().addClass('is-invalid');
            dataError = true;
        }

        var gameTime = $('#gameTime').val();
        if(gameTime == '') {
            $('#gameTime').parent().addClass('is-invalid');
            dataError = true;
        }

        var tradePos = $('#tradePos').val();
        if(tradePos == '') {
            $('#tradePos').parent().addClass('is-invalid');
            dataError = true;
        }
        var data = {
            title: title,
            itemname: staff,
            itemnum: quantity,
            itemprice: price,
            itemquality: quality,
            trader: poster,
            onlinetime: gameTime,
            tradingplace: tradePos
        }
        console.log(data);

        var msg = {message: '保存成功'};
        snackbarContainer.MaterialSnackbar.showSnackbar(msg);
    })
});