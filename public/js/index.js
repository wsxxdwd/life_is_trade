$(document).ready(function() {
    var snackbarContainer = document.querySelector('#toast');
    getInfoList('sell');
    getInfoList('buy');
    getInfoList('news');

    var isloading = false;
    var isSearch = false;

    var backTopBtn = $('.back-top');
    $('main').scroll(throttle(function() {
        var scrollTop = $('main').scrollTop() + $(window).height();
        var pageHeight = $('.mdl-layout__tab-panel.is-active').height();
        console.log(scrollTop ,pageHeight)
        if (scrollTop > pageHeight) {
            if (!isloading && !isSearch) {
                var type = $('.mdl-layout__tab-panel.is-active').attr('id');
                getInfoList(type);
            }
        }
        if(scrollTop > 1200) {
            backTopBtn.fadeIn()
        } else {
            backTopBtn.fadeOut('fast');
        }
    }, 300, this));

    $('.mdl-layout--fixed-header').scroll(throttle(function() {
        var scrollTop = $('.mdl-layout--fixed-header').scrollTop() + $(window).height();
        var pageHeight = $('.mdl-layout__tab-panel.is-active').height();
        console.log(scrollTop ,pageHeight)
        if (scrollTop > pageHeight) {
            if (!isloading && !isSearch) {
                var type = $('.mdl-layout__tab-panel.is-active').attr('id');
                getInfoList(type);
            }
        }
        if(scrollTop > 1200) {
            backTopBtn.fadeIn()
        } else {
            backTopBtn.fadeOut('fast');
        }
    }, 300, this));

    $('#search').on('keyup', throttle(function() {
        var wd = $(this).val();
        if($.trim(wd) === '') {
            isSearch = false;
        } else {
            isSearch = true;
        }
        if (!isloading) {
            var wd = wd;
            var type = $('.mdl-layout__tab-panel.is-active').attr('id');
            search(wd, type);
        }
    }, 800, $('#search')));

    $('.mdl-layout__tab').click(function() {
        var type = $('.mdl-layout__tab-panel.is-active').attr('id');
        isSearch = false;
        $('#search').val('');
        $('#' + type).html('');
        getInfoList(type)
    });

    backTopBtn.click(function() {
        $('.mdl-layout--fixed-header, main').scrollTop(0);
    });
    function getInfoList(type, lastid) {
        var lastid = typeof lastid !== 'undefined' ? lastid : $('.is-active section').last().length ? $('.is-active section').last().data('id') : false;
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
                    if (res.status === 1) {
                        renderPage($('#sell'), res.data);
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
                    if (res.status === 1) {
                        renderPage($('#buy'), res.data);
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
                    if (res.status === 1) {
                        renderNews(res.data);
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

    function renderNews(data) {
        var html = '';
        for (var i in data) {
            var info = data[i];
            html += makeNews(info);
        }
        $('#news').append(html);
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

    function makeNews(data) {
        var html = ['<section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">',
            '<div class="mdl-card mdl-cell mdl-cell--12-col">',
            '<div class="mdl-card__supporting-text">',
            '<h4>', data.title, '</h4>',
            data.content,
            '</div>',
            '</div>',
            '</section>'
        ].join('');
        return html;
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

    function search(wd, type) {
        var typePanel = $('#' + type).html('');
        if(!isSearch) {
            typePanel.html('');
            getInfoList(type);
            return;
        }
        var lastid = $('.is-active section').last().length ? $('.is-active section').last().data('id') : false;
        isloading = true;
        if (type === 'sell') {
            var searchType = 0;
        } else if (type === 'buy') {
            var searchType = 1;
        }
        $.ajax({
            type: 'get',
            url: '/api/search',
            data: {
                wd: wd,
                lastid: lastid,
                limit: 100,
                type: searchType
            },
            dataType: 'json',
            success: function(res) {
                isloading = false;
                if (res.status === 1) {
                    typePanel.html('');
                    renderPage($('#' + type), res.data);
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
});

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
