(function (jQuery, Drupal, drupalSettings) {
  Drupal.behaviors.donation_paypal_ponctuel = {
    attach: function (context) {
      jQuery('#paypal-button-container', context).each(function (key, element) {
        paypal.Buttons({
          locale: 'fr_FR',
          style: {
            color:  'white',
            shape:  'rect',
            label:  'buynow',
            height: 40,
            tagline: false,
            shape: 'rect',
            size: 'responsive'
          },
          createOrder: function(data, actions) {
            return actions.order.create({
              purchase_units: [{
                description: "Soutien " + element.getAttribute('data-don'),
                amount: {
                  currency_code: "EUR",
                  value: element.getAttribute('data-don'),
                  breakdown: {
                    item_total: {
                      currency_code: "EUR",
                      value: element.getAttribute('data-don')
                    }
                  }
                },
                items: [
                  {
                    name: "Soutien " + element.getAttribute('data-don'),
                    description: "Merci pour votre soutien de " + element.getAttribute('data-don'),
                    sku: "SOUTIEN-"+ element.getAttribute('data-don'),
                    unit_amount: {
                      currency_code: "EUR",
                      value: element.getAttribute('data-don')
                    },
                    quantity: "1"
                  },
                ],
              }]
            });
          },
          onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
              fetch('/gm-adhesion/approve', {
                method: 'post',
                headers: new Headers({
                  'Content-type': 'application/json'
                }),
                body: JSON.stringify({
                    order_id: data.orderID,
                    capture_data: data,
                    capture_details: details,
                })
              }).then(function() {
                window.location.replace("http://www.w3schools.com");
              })
            });
          },
        }).render(element);
      
      })
    }
  }
})(jQuery, Drupal, drupalSettings);