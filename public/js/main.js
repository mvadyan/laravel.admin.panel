/*Подтверждение удаления заказа*/

var alertMessage = $('.alert_message');

if (alertMessage.length) {
    setTimeout(function () {
        alertMessage.hide('slow').remove();
    }, 2000);
}

$('.delete').click(function () {
    var res = confirm('Подтвердите действие');

    if (!res) return false;
})

/*Подсветка активного меню*/

$('.sidebar-menu li').find('a').each(function () {
    var location = window.location.protocol + '//' + window.location.host + window.location.pathname
    var link = this.href;

    if (link === location) {
        $(this).parent().addClass('active');
        $(this).closest('.treeview').addClass('active')
    }
})
/*KCEditor*/
$('#editor1').ckeditor();

/*Сброс фильтра админка*/
$('#reset-filter').click(function () {
    $('#filter input[type=radio]').prop('checked', false);
    return false;
});

/*Выбор категории*/

$('#add').on('submit', function () {
    if (!isNumber($('#parent_id').val())) {
        alert('Выберите категорию');
        return false;
    }
})

/*Является ли поле числом*/
function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}


var route = '/admin/autocomplete';

$('#search').typeahead({
    source: function (term, process) {
        return $.get(route, {term: term}, function (data) {
            return process(data);
        });
    }
});
