/*Подтверждение удаления заказа*/

var alertMessage = $('.alert_message');

if (alertMessage.length) {
    setTimeout(function () {
        alertMessage.hide('slow').remove();
    }, 2000);
}

console.log(alertMessage.length);

$('.delete').click(function () {
    var res = confirm('Подтвердите действие');

    if (!res) return false;
})
