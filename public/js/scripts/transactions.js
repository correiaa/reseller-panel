var transactionsTable = $('#transactions').DataTable( {
  //"processing": true,
  "lengthMenu": [10, 50, 100],
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
      "targets": 3, //type
      "searchable": false,
      "render": function(data, type, row, meta){
        return (data == 'res_to_res') ? 'Admin to reseller' : 'Reseller to customer';
      }
    },
    {
      "targets": 4, //sender
      "searchable": false,
    },
    {
      "targets": 5, //recipient
      "searchable": false,
    },

  ],
  // 'iDisplayLength': 11,
  "sPaginationType": "full_numbers",
  "dom": 'T<"clear">lfrtip'

});
