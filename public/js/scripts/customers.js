var customersTable = $('#customers').DataTable( {
  //"processing": true,
  "lengthMenu": [10, 50, 100],
  // "bLengthChange": false,
  // "pageLength": 5,
  "serverSide": true,
  "ajax": {
    "url": "/datatables/customers",
    "type": "POST"
  },
  "columnDefs": [
  {
    "targets": 0, //id
    "visible": true,
    "searchable": false
  },
  {
    "targets": 1, //account_number
    "visible": true,
    "searchable": true,
    "render": function(data, type, row, meta){
      return '<a href=/customers/'+row[0]+'>'+row[1]+'</a>';
    }
  },
  {
    "targets": 2, //mac
    "searchable": true,
    "render": function(data, type, row, meta){
      return '<a href=/customers/'+row[0]+'>'+row[2]+'</a>';
    }
  },
  {
    "targets": 3, //login
    "searchable": true,
    "render": function(data, type, row, meta){
      return '<a href=/customers/'+row[0]+'>'+row[3]+'</a>';
    }
  },
  {
    "targets": 4, //expire date
    "render": function(data, type, row, meta){
      var expireDate = (data == '0000-00-00 00:00:00' || data == null) ? 'Unlimited' : data;
      return '<a href=/customers/'+row[0]+'>'+expireDate+'</a>';
    }
  },
  {
    "targets": 5, //status
    "bSortable": false,
    "searchable": false,
    "render":  function(data, type, row, meta) {

        var icon = (data == true) ? 'nc-check-2 text-success' : 'nc-simple-remove text-danger';

        var status = !(data == true);

        return '<a href="/customers/'+row[0]+'/status/'+ status +'"><i class="nc-icon ' + icon + '"></i></a>';

      }
    },
    {
      "targets": 6, //delete
      'bSortable': false,
      "searchable": false,
      "render": function(data, type, row, meta){
        var action = '/customers/delete/'+row[0];
        //return '<button class="btn" data-href="'+action+'" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></button>';
        return '<a href="/customers/delete/'+row[0]+'"><i class="nc-icon nc-basket"></i></a>';

      }
    }

  ],
  // 'iDisplayLength': 11,
  "sPaginationType": "full_numbers",
  "dom": 'T<"clear">lfrtip',

});

// $('.datetimepicker').datetimepicker({
//     icons: {
//         time: "fa fa-clock-o",
//         date: "fa fa-calendar",
//         up: "fa fa-chevron-up",
//         down: "fa fa-chevron-down",
//         previous: 'fa fa-chevron-left',
//         next: 'fa fa-chevron-right',
//         today: 'fa fa-screenshot',
//         clear: 'fa fa-trash',
//         close: 'fa fa-remove'
//     }
// });
