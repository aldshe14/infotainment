$(document).ready(function() {
  $('#hide').fadeOut(5000); // 5 seconds x 1000 milisec = 5000 milisec
});

  $(document).ready(function() {
    var t = $('#resetuserdata').DataTable({
      select: true,
      colReorder: true,
      orderCellsTop: true,
      fixedHeader: false,
      keys: true,
      fixedColumns:   true,
      "bAutoWidth": false,
      "processing": true,
      dom: 'CBRSflrtip',
      buttons: [
          {
            extend: 'csv',
            title: "",
            messageTop: "",
            messageBottom: "",
            exportOptions:{
              columns: ':visible'
            },
            header : true
         },
          {
            extend: 'print',
            title: "",
            messageTop: "",
            messageBottom: "",
            header : true,
            exportOptions:{
              columns: ':visible'
            },
            "fixedHeader":  {
              footer : false
          },
          
         },
         "columnsVisibility"
      ],
      "columnDefs": [ {
        "targets": 0,
        "orderable": false
        } ],
        "processing": true,
      lengthChange: true,
      language:{
        select: {
          rows: {
              _: "%d rreshta te selektuar",
              0: "Kliko nje rresht per ta selektuar",
              1: "1 rresht i selektuar"
          }
        },
        "sEmptyTable":     "Nuk ka asnjë të dhënë në tabelë",
        "sInfo":           "Duke treguar _START_ deri _END_ prej _TOTAL_ reshtave",
        "sInfoEmpty":      "Duke treguar 0 deri 0 prej 0 reshtave",
        "sInfoFiltered":   "(të filtruara nga gjithësej _MAX_  reshtave)",
        "sInfoPostFix":    "",
        "sInfoThousands":  ",",
        "sLengthMenu":     "Shfaq _MENU_ rreshta",
        "sLoadingRecords": "Duke punuar...",
        "sProcessing":     "Duke procesuar...",
        "sSearch":         "Kërko:",
        "sZeroRecords":    "Asnjë e dhënë nuk u gjet",
        "oPaginate": {
            "sFirst":    "E para",
            "sLast":     "E Fundit",
            "sNext":     "Tjetra",
            "sPrevious": "E Kaluara"
        },
        "oAria": {
            "sSortAscending":  ": aktivizo për të sortuar kolonen me vlera në ngritje",
            "sSortDescending": ": aktivizo për të sortuar kolonen me vlera në zbritje"
        },
        "buttons": {
          "csv": "Excel",
          "print":    "Printo",
          "colvis":   "Kolona",
          "copy":     "Kopjo",
          "copyTitle":    "Kopjo në clipboard",
          "copyKeys": "Taste <i>ctrl</i> oder <i>\u2318</i> + <i>C</i> um Tabelle<br>in Zwischenspeicher zu kopieren.<br><br>Um abzubrechen die Nachricht anklicken oder Escape drücken.",
          "copySuccess": {
              "_": "%d Kolona u kopjuan",
              "1": "1 Kolone u kopjua"
          }
        }
    }
    });

    t.on( 'order.dt search.dt', function () {
      t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
         cell.innerHTML = i + 1;
          t.cell(cell).invalidate('dom'); 
      } );
   } ).draw();

   $('#resetuserdata thead tr').clone(true).appendTo( '#resetuserdata thead' );
  $('#resetuserdata thead tr:eq(1) th').each( function (i) {
      var title = $(this).text();
      $(this).html( '<input type="text" placeholder="Search '+title+'" />' );

      $( 'input', this ).on( 'keyup change', function () {
          if ( t.column(i).search() !== this.value ) {
              t
                  .column(i)
                  .search( this.value )
                  .draw();
          }
      } );
  } );
    
} );
