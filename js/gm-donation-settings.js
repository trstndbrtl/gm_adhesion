(function ($, Drupal, drupalSettings) {
  // console.log("donation_paypal mensuel")
  Drupal.behaviors.donation_settings = {
    attach: function (context) {
      $('#gm-donateur-address input', context).each(function (key, element) {
        // var $input_element = $(this);
        // $input_element.keyup(function(event) {
        //   // alert( "Handler for .keyup() called." );
        //   var $input_element = $(this);
        //   var current_value = $input_element.val();
        //   // var url = 'https://geo.api.gouv.fr/communes?codePostal=75020&fields=nom,code,codesPostaux,codeDepartement,codeRegion,population&format=json&geometry=centre'
        //   var url = 'https://api-adresse.data.gouv.fr/search/?q=' + current_value

        //   if (current_value != '') {

        //     fetch(url)
        //       .then((resp) => resp.json())
        //       .then(function(data) {
        //         // console.log(data)

        //       //   var list_wrapper = document.getElementById('gm-donateur-address-items');


        //       //   var list = '<div>hello</div>' + current_value;
        //       //   list = '<div>hello</div>' + current_value;

        //       //   let items = [
        //       //     'Blue',
        //       //     'Red',
        //       //     'White',
        //       //     'Green',
        //       //     'Black',
        //       //     'Orange'
        //       // ],

        //       ul = document.createElement('ul');
        //       // If request send element
        //       let dat_element = (data && data.features && data.features.length > 0) ? data.features : null
        //       if (dat_element) {
        //         $('#gm-donateur-address-items').replaceWith(ul)
        //         dat_element.forEach(function (item) {
        //             console.log(item.properties)
        //             let li = document.createElement('li');
        //             ul.appendChild(li);
        //             var a = document.createElement('a');
        //             a.setAttribute('href','#');
        //             a.setAttribute('data-code', item.properties.postcode);
        //             a.setAttribute('data-city', item.properties.city);
        //             a.setAttribute('data-adresse', item.properties.name);
        //             a.setAttribute('class', 'gm-choose-city');
        //             a.innerHTML = item.properties.name + ' - ' + item.properties.postcode + ' - ' + item.properties.city;
        //             li.appendChild(a);
        //             // // apend the anchor to the body
        //             // li.innerHTML += item.properties.city;
        //             // li.innerHTML += item;
        //         });

        //         $('a.gm-choose-city').on('click.searchApiUpdate', function (event) {
        //           var $link = $(this);
        //           var current_city = $link.attr('data-code');
        //           var current_code = $link.attr('data-city');
        //           var current_addresse = $link.attr('data-adresse');
        //           console.log(current_city)
        //           console.log(current_code)
        //           console.log(current_addresse)
        //             // $('#gm-section-menu-icon select, #gm-section-icon select').val(current_key);
        //             // $('#gm-build-icon-info #gm-current-icon i').removeClass().addClass("bi-"+current_key)
        //             event.preventDefault()
        //         });
        //       }

             
        //     })
        //     .catch(function(error) {
        //       console.log(error);
        //     });
            
        //   }
         

        // });
        
      })

      // $('a.gm-choose-city', context).each(function (key, element) {
      //   // the processor's table row and vertical tab pane.
        
      // })

    }
  };
  /**
 * Namespace for MzineApp related functionality.
 *
 * @namespace
 */
    Drupal.donateSetting = {

    /**
     * A {@link Drupal.donateSetting.App} instance.
     */
    app: {},
  };

  Drupal.donateSetting.app = {

    initializeCollection: function (context) {
      // define MzineApp
      var App = Drupal.donateSetting;
    },

    selectCity: function (data) {
      console.log(data)
      
    },

  };
})(jQuery, Drupal, drupalSettings);