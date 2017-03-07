/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function () {
    payment.init();
});

payment = function () {
    return {
        init: function () {
            payment.addEvents(jQuery('body'));

        }, addEvents: function (html) {

            html.find('form').submit(function () {
                // return payment.savePaymentJson(jQuery(this));
                // return false;
            });

            html.find('.process-payment').submit(function () {
                // return payment.savePaymentJson(jQuery(this));
            });

            html.find('.payment-gateway-radio').click(function () {

                jQuery('.payment-gateway-radio').removeAttr('checked');

                jQuery(this).attr('checked', 'checked');
                alert('ok');
            });

            html.find('.payment-gateway-link').click(function () {
                payment.changePaymentGateway(jQuery(this));
            });

            html.find('.payment-gateway-radio').on('ifChecked', function (event) {
                alert('ok ok');
                payment.changePaymentGateway(jQuery(this));
            });

            return html;
        }, changePaymentGateway: function (this_element) {

            var closest_input = this_element.closest('li').find('input');
            //  var gateway = this_element.attr('gateway');

            //  jQuery('.gateway-content').hide("slow");

            // jQuery('.' + gateway + '-gateway-content').show('slow');
            closest_input.prop("checked", true);


        }, savePaymentJson: function (this_element) {

            var payment_id = jQuery('.payment_id').val();
            var payment_params = jQuery('.payment_params').val();
            var payment_type = jQuery('.payment_type').val();
            var data_object = {payment_id: payment_id, payment_params: payment_params, payment_type: payment_type};

            var form = kazist.callAjaxByRoute('finance.payments.ajaxsavejson', data_object, true);

            console.log(data_object);

            return false;

        }
    };
}();