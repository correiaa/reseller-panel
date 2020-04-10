jQuery(document).ready(function($) {

  $("#new_reseller").validate({
                rules: {
                  name: {
                    required: true,
                  },
                  login: {
                    required: true,
                  },
                  password: {
                    required: true,
                  },
                  balance: {
                    digits: true,
                    required: true,
                  },
                },
                messages: {
                  balance: {
                    digits: "Please enter a positive number of credits"
                  }
                }
          });
});

var resellersTable = $('#resellers').DataTable( {
  //"processing": true,
  "lengthMenu": [10, 50, 100],
  // "bLengthChange": false,
  // "pageLength": 5,
  "serverSide": true,
  "ajax": {
    "url": "/datatables/resellers",
    "type": "POST"
  },
  "columnDefs": [
  {
    "targets": 0, //id
    "visible": true,
    "searchable": false
  },
  {
    "targets": 1, //name
    "visible": true,
    "searchable": true,
    "render": function(data, type, row, meta){
      return '<a href=/resellers/'+row[0]+'>'+row[1]+'</a>';
    }
  },
  {
    "targets": 2, //balance
    "searchable": false,
    // "render": function(data, type, row, meta){
    //   return '<a href=/resellers/'+row[0]+'>'+row[2]+'</a>';
    // }
  },
  {
    "targets": 3, //total customers
    "searchable": false,
    // "render": function(data, type, row, meta){
    //   return '<a href=/customers/'+row[0]+'>'+row[3]+'</a>';
    // }
  },
  {
    "targets": 4, //active customers
    "searchable": false,
    // "render": function(data, type, row, meta){
    //   return '<a href=/customers/'+row[0]+'>'+expireDate+'</a>';
    // }
  },
  {
    "targets": 5, //last_active
    "searchable": false,
    // "render": function(data, type, row, meta){
    //   return '<a href=/customers/'+row[0]+'>'+expireDate+'</a>';
    // }
  },
  {
    "targets": 6, //status
    "bSortable": false,
    "searchable": false,
    "render":  function(data, type, row, meta) {

        var icon = (data == true) ? 'nc-check-2 text-success' : 'nc-simple-remove text-danger';

        var status = !(data == true);

        return '<a href="/resellers/'+row[0]+'/status/'+ status +'"><i class="nc-icon ' + icon + '"></i></a>';
      }
    }
  ],
  // 'iDisplayLength': 11,
  "sPaginationType": "full_numbers",
  "dom": 'T<"clear">lfrtip',

});
