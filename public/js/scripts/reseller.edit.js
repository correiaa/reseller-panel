jQuery(document).ready(function($) {

  $("#edit_account").validate({
                rules: {
                  password2: {
                    equalTo: "#password1",
                  },
                  balance: {
                    digits: true,
                    required: true,
                  },
                  name: {
                    required: true,
                  }
                },
                messages: {

                }
          });
});

var transactionsTable = $('#transactions').DataTable( {
  //"processing": true,
  "bLengthChange": false,
  "pageLength": 10,
  "columnDefs": [
    {
      "targets": 0, //id
      "visible": true,
      "searchable": false
    },
    {
      "targets": 1, //date
      "visible": true,
      "searchable": true
    },
    {
      "targets": 2, //amount
      "searchable": true
    },
  ],
  // 'iDisplayLength': 11,
  "sPaginationType": "full_numbers",
  "dom": 'T<"clear">lfrtip'

});
