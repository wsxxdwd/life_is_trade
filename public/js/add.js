$(document).ready(function() {
    var snackbarContainer = document.querySelector('#toast');
    var isPublishing = false;
    $('#publishBtn').click(function(e) {
        e.preventDefault();
        if (!isPublishing) {
            var data = getFormData();
            if (!!data) {
                isPublishing = true;
                $.ajax({
                    type: 'post',
                    data: data,
                    url: '/home/api/addtradeinfo',
                    dataType: 'json',
                    success: function(res) {
                        isPublishing = false;
                        if (res.status === 1) {
                            var msg = { message: '发布成功' };
                            window.location = '/';
                        } else {
                            var msg = { message: '发布失败' };
                        }
                        snackbarContainer.MaterialSnackbar.showSnackbar(msg);
                    },
                    error: function(err) {
                        isPublishing = false;
                        var msg = { message: '发布失败' };
                        snackbarContainer.MaterialSnackbar.showSnackbar(msg);
                    }
                })
            }
        }
    });
    $('#addItemBtn').click(function(e) {
        e.preventDefault();
        var index = $('.item-list .item').last().data('index');
        var html = template(index + 1);
        $('.item-list').append(html);
        componentHandler.upgradeDom();

    });
    $('.item-list').on('click', '.removeItemBtn', function(e) {
        e.preventDefault();
        console.log($(this).parent())
        $(this).parent().remove();
    });

    $('.map').on('click', function(ev) {
        var top = (ev.offsetY / $(this).height()) * 100 + '%';
        var left = (ev.offsetX / $(this).width()) * 100 + '%';
        $('.pointer').css({
            top: top,
            left: left
        });
    });
    function getFormData() {
        var dataError = false;

        var poster = $('#poster').val();
        if (poster == '') {
            $('#poster').parent().addClass('is-invalid');
            dataError = true;
        }

        var type = $('[name="type"]:checked').val();

        var title = $('#title').val();
        if (title == '') {
            $('#title').parent().addClass('is-invalid');
            dataError = true;
        }
        var itemsDom = $('.item');
        var items = [];
        for (var i = 0; i < itemsDom.length; i++) {
            var item = itemsDom.eq(i);
            var staff = item.find('.staff').val();
            if (staff == '' ||
                staff.length > 255) {
                $('#staff').parent().addClass('is-invalid');
                dataError = true;
            }

            var quantity = item.find('.quantity').val();
            if (quantity == '' ||
                isNaN(quantity * 1) ||
                quantity > 9999 ||
                quantity < 0) {
                item.find('.quantity').parent().addClass('is-invalid');
                dataError = true;
            }

            var quality = item.find('.quality').val();
            if (quality == '' ||
                isNaN(quality * 1) ||
                quality > 100 ||
                quality < 0) {
                item.find('.quality').parent().addClass('is-invalid');
                dataError = true;
            }

            var price = item.find('.price').val();
            if (price == '') {
                item.find('.price').parent().addClass('is-invalid');
                dataError = true;
            }
            items[i] = {
                itemname: staff,
                itemnum: quantity * 1,
                itemprice: price,
                itemquality: quality * 1,
            }
        }


        var gameTime = $('#gameTime').val();
        if (gameTime == '') {
            $('#gameTime').parent().addClass('is-invalid');
            dataError = true;
        }

        var tradePos = $('#tradePos').val();
        if (tradePos == '') {
            $('#tradePos').parent().addClass('is-invalid');
            dataError = true;
        }
        var positionTop =  $('.pointer').css('top');
        var positionLeft =  $('.pointer').css('left');
        if (dataError) {
            var msg = { message: '请完善信息' };
            snackbarContainer.MaterialSnackbar.showSnackbar(msg);
            return false;
        } else {
            var data = {
                title: title,
                tradetype: type * 1,
                items: items,
                trader: poster,
                onlinetime: gameTime,
                tradingplace: tradePos,
                toppos: positionTop,
                leftpos: positionLeft 
            }
            return data;
        }
    }

    function template(index) {
        var html = ['<div class="mdl-grid item" data-index="', index, '">',
            '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-cell--3-col mdl-cell">',
            '<input class="mdl-textfield__input staff" type="text" >',
            '<label class="mdl-textfield__label" for="staff">物品</label>',
            '<span class="mdl-textfield__error">必须填写</span>',
            '</div>',
            '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-cell--3-col mdl-cell">',
            '<input class="mdl-textfield__input quantity" type="text" pattern="[0-9]{0,4}">',
            '<label class="mdl-textfield__label" for="quantity">数量</label>',
            '<span class="mdl-textfield__error">1~9999</span>',
            '</div>',
            '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-cell--2-col mdl-cell">',
            '<input class="mdl-textfield__input quality" type="text" pattern="[0-9]{0,3}">',
            '<label class="mdl-textfield__label" for="quality">品质</label>',
            '<span class="mdl-textfield__error">1~100</span>',
            '</div>',
            '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-cell--2-col mdl-cell">',
            '<input class="mdl-textfield__input price" type="text">',
            '<label class="mdl-textfield__label" for="price">价格</label>',
            '<span class="mdl-textfield__error">必须填写，如：5J8Y200T</span>',
            '</div>',
            '<button class="removeItemBtn mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-cell--1-col mdl-cell">',
            '删 除',
            '</button>',
            '</div>'
        ].join('');
        return html;
    }
});
