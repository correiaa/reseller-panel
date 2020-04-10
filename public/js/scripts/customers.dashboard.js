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

  $("#new_account").validate({
                rules: {
                  stb_mac: {
                    required: function(element) {
                      return $("#login").val() == false;
                    },
                    remote: {
                      url: '/customers/validate-mac',
                      type: 'post',
                    },
                    pattern: /^([0-9A-Fa-f]{2}:){5}([0-9A-Fa-f]{2})$/
                  },
                  login: {
                    required: function(element) {
                      return $("#stb_mac").val() == false;
                    },
                    remote: {
                      url: '/customers/validate-login',
                      type: 'post',
                    }
                  },
                  password: {
                    required: function(element) {
                      return $("#login").val() != false;
                    }
                  },
                  subscription: {
                    required: true,
                    remote: {
                      url: '/resellers/check-available-balance',
                      type: 'post',
                    }
                  }
                },
                messages: {

                }
          });
});
