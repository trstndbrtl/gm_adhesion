(function (jQuery, Drupal, drupalSettings) {
  Drupal.behaviors.donation_paypal_mensuel = {
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
          createSubscription: function(data, actions) {
            //let plan = $("#frequency").data("paypal");
            return actions.subscription.create({
              custom_id : '654565',
              plan_id: element.getAttribute('data-don'),
            });
          },
          onApprove: function (data, actions) {
            fetch('/gm-adhesion/approve', {
              method: 'post',
              headers: new Headers({
                'Content-type': 'application/json'
              }),
              body: JSON.stringify({
                order_id: data.orderID,
                capture_data: data,
                // capture_details: details,
              })
            }).then(function() {
              window.location.replace("http://www.w3schools.com");
            })

            // Get the order details
            return actions.subscription.get().then(function (orderDetails) {
              // console.log(orderDetails)
              fetch('/gm-donation/subscription', {
                method: 'post',
                headers: new Headers({
                  'Content-type': 'application/json'
                }),
                body: JSON.stringify({
                  details: {
                    capture_details: orderDetails,
                    plan_id: element.getAttribute('data-don'),
                    amount: element.getAttribute('data-amount'),
                    type: element.getAttribute('data-type')
                  },
                })
              }).then(function() {
                console.log('subscription')
                console.log(orderDetails)
              })

            });
          },
        }).render(element);
      })
    }
  }
})(jQuery, Drupal, drupalSettings);