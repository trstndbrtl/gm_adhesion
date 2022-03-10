(function (jQuery, settings) {
  Drupal.behaviors.donation_paypal = {
    attach: function (context) {
      jQuery('.paypal-button', context).each(function (key, element) {
        paypal.Buttons({
          style: JSON.parse(element.getAttribute('data-settings')),
          createOrder: function (data, actions) {
            return actions.order.create({
              purchase_units: [{
                amount: {
                  value: element.getAttribute('data-value'),
                }
              }]
            });
          },
          onApprove: function (data, actions) {
            actions.order.capture().then(function (details) {
              fetch('/gm_adhesion/approve', {
                method: 'post',
                headers: new Headers({
                  'Content-type': 'application/json'
                }),
                body: JSON.stringify({
                  details: details,
                  element: element.getAttribute('id'),
                })
              }).then(function() {
                window.location.reload();
              })
            });

          },
        }).render(element);
      })
    }
  }
})(jQuery, drupalSettings);
