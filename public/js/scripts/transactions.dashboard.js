var transactionsTable = $('#transactions').DataTable( {
  //"processing": true,
  "bLengthChange": false,
  "pageLength": 5,
  "serverSide": true,
  "ajax": {
    "url": "/datatables/transactions",
    "type": "POST"
  },
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
  {
    "targets": 3, //customers.login
    "searchable": true,
    "render": function(data, type, row, meta){
      return '<a href=/customers/'+row[4]+'>'+data+'</a>';
    }
  },
  {
    "targets": 4, //customer_id
    "searchable": false,
    "visible": false
  },

  ],
  // 'iDisplayLength': 11,
  "sPaginationType": "full_numbers",
  "dom": 'T<"clear">lfrtip'

});
