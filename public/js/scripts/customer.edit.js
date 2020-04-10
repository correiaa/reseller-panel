jQuery(document).ready(function($) {

  $.validator.addMethod( "pattern", function( value, element, param ) {

    if ( this.optional( element ) ) {
      return true;
    }

    if ( typeof param === "string" ) {
      param = new RegExp( "^(?:" + param + ")$" );
    }

    return param.test( value );

    }, "Please, enter the valid mac-address" );

  $("#edit_account").validate({
                rules: {
                  stb_mac: {
                    remote: {
                      url: '/customers/validate-mac/',
                      type: 'post',
                      data: {
                        customer_id: function() {
                          return $("#customer_id").val();
                        }
                      }
                    },
                    pattern: /^([0-9A-Fa-f]{2}:){5}([0-9A-Fa-f]{2})$/
                  },
                  password: {
                    required: function(element) {
                      return $("#stb_mac").val() == false;
                    },
                  },
                  end_date: {
                    required: true,
                    remote: {
                      url: '/resellers/check-available-balance',
                      type: 'post',
                      data: {
                        customer_id: function() {
                          return $("#customer_id").val();
                        }
                      }
                    }
                  }
                },
                messages: {

                }
          });
});
