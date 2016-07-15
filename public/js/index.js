$(document).ready(function() {
    var snackbarContainer = document.querySelector('#toast');
    getInfoList('sell');
    getInfoList('buy');
    getInfoList('news');

    var isloading = false;

    $('main').scroll(throttle(function() {
        var scrollTop = $('main').scrollTop() + $(window).height();
        var pageHeight = $('.mdl-layout__tab-panel.is-active').height();
        console.log(scrollTop, pageHeight)
        if (scrollTop > (pageHeight - 100)) {
            if (!isloading) {
                var type = $('.mdl-layout__tab-panel.is-active').attr('id');
                getInfoList(type);
            }
        }
    }, 300, this));

    function getInfoList(type) {
        var lastid = $('.is-active section').last().length ? $('.is-active section').last().data('id') : false;
        if (type === 'sell') {
            isloading = true;
            $.ajax({
                type: 'get',
                url: '/api/gettradeinfo',
                data: {
                    type: 0,
                    lastid: lastid,
                    limit: 10
                },
                dataType: 'json',
                success: function(res) {
                    isloading = false;
                    if (res.status == 1) {
                        renderPage($('#sell'), res.data);
                    } else {
                        var msg = { message: '获取失败' };
                        snackbarContainer.MaterialSnackbar.showSnackbar(msg);

                    }
                },
                error: function(err) {
                    isloading = false;
                    var msg = { message: '获取失败' };
                    snackbarContainer.MaterialSnackbar.showSnackbar(msg);
                    console.log(err);
                }
            });
        } else if (type === 'buy') {
            isloading = true;
            $.ajax({
                type: 'get',
                url: '/api/gettradeinfo',
                data: {
                    type: 1,
                    lastid: lastid,
                    limit: 10
                },
                dataType: 'json',
                success: function(res) {
                    isloading = false;
                    if (res.status == 1) {
                        renderPage($('#buy'), res.data);
                    } else {
                        var msg = { message: '获取失败' };
                        snackbarContainer.MaterialSnackbar.showSnackbar(msg);

                    }
                },
                error: function(err) {
                    isloading = false;
                    var msg = { message: '获取失败' };
                    snackbarContainer.MaterialSnackbar.showSnackbar(msg);
                    console.log(err);
                }
            });
        } else if (type === 'news') {
            isloading = true;
            $.ajax({
                type: 'get',
                url: '/api/getnews',
                data: {
                    lastid: lastid,
                    limit: 10
                },
                dataType: 'json',
                success: function(res) {
                    isloading = false;
                    if (res.status == 1) {
                        renderPage($('#buy'), res.data);
                    } else {
                        var msg = { message: '获取失败' };
                        snackbarContainer.MaterialSnackbar.showSnackbar(msg);

                    }
                },
                error: function(err) {
                    isloading = false;
                    var msg = { message: '获取失败' };
                    snackbarContainer.MaterialSnackbar.showSnackbar(msg);
                    console.log(err);
                }
            });
        }
    }

    function renderPage(page, data) {
        for (var i in data) {
            var info = data[i];
            var table = '';
            for (var i in info.items) {
                table += makeTable(info.items[i]);
            }
            var html = [
                '<section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp" data-id="', info.tid, '">',
                '<div class="mdl-card mdl-cell mdl-cell--12-col">',
                '<div class="mdl-card__supporting-text">',
                '<h4>', info.title, '</h4>',
                '<h6>交易者：', info.trader, '</h6>',
                '<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">',
                '<thead>',
                '<tr>',
                '<th class="mdl-data-table__cell--non-numeric">物品</th>',
                '<th>数量</th>',
                '<th>质量</th>',
                '<th>价格</th>',
                '</tr>',
                '</thead>',
                '<tbody>',
                table,
                '</tbody>',
                '</table>',
                '<h6>交易地点： ', info.tradingplace, '</h6>',
                '<h6>在线时间： ', info.onlinetime, '</h6>',
                '</div>',
                '</div>',
                '</section>'
            ].join('');
            page.append(html);
        }
        componentHandler.upgradeDom();
    }

    function makeTable(data) {
        var tr = [
            '<tr>',
            '<td class="mdl-data-table__cell--non-numeric">', data.itemname, '</td>',
            '<td>', data.itemnum, '</td>',
            '<td>', data.itemprice, '</td>',
            '<td>', data.itemquality, '</td>',
            '</tr>'
        ].join('');
        return tr;
    }

    function throttle(method, delay, context) {
        var timer = null;
        var context = context ? context : this,
            args = arguments;
        return function() {
            clearTimeout(timer);
            timer = setTimeout(function() {
                method.apply(context, args);
            }, delay);
        }

    }

});
