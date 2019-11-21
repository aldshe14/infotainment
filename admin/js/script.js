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
      "autoWidth": true,
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
            messageTop: function (){
              return "";
            },
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
        "orderable": true
        } ],
        "processing": true,
      lengthChange: true,
      language:{
        "sEmptyTable":      "Keine Daten in der Tabelle vorhanden",
        "sInfo":            "_START_ bis _END_ von _TOTAL_ Einträgen",
        "sInfoEmpty":       "Keine Daten vorhanden",
        "sInfoFiltered":    "(gefiltert von _MAX_ Einträgen)",
        "sInfoPostFix":     "",
        "sInfoThousands":   ".",
        "sLengthMenu":      "_MENU_ Einträge anzeigen",
        "sLoadingRecords":  "Wird geladen ..",
        "sProcessing":      "Bitte warten ..",
        "sSearch":          "Suchen",
        "sZeroRecords":     "Keine Einträge vorhanden",
        "oPaginate": {
            "sFirst":       "Erste",
            "sPrevious":    "Zurück",
            "sNext":        "Nächste",
            "sLast":        "Letzte"
        },
        "oAria": {
            "sSortAscending":  ": aktivieren, um Spalte aufsteigend zu sortieren",
            "sSortDescending": ": aktivieren, um Spalte absteigend zu sortieren"
        },
        "select": {
            "rows": {
                "_": "%d Zeilen ausgewählt",
                "0": "",
                "1": "1 Zeile ausgewählt"
            }
        },
        "buttons": {
            "print":    "Drucken",
            "colvis":   "Spalten",
            "copy":     "Kopieren",
            "copyTitle":    "In Zwischenablage kopieren",
            "copyKeys": "Taste <i>ctrl</i> oder <i>\u2318</i> + <i>C</i> um Tabelle<br>in Zwischenspeicher zu kopieren.<br><br>Um abzubrechen die Nachricht anklicken oder Escape drücken.",
            "copySuccess": {
                "_": "%d Zeilen kopiert",
                "1": "1 Zeile kopiert"
            },
            "pageLength": {
                "-1": "Zeige alle Zeilen",
                "_":  "Zeige %d Zeilen"
            }
        }
    }
    
    });

    

    //$('#resetuserdata thead tr').clone(true).appendTo( '#resetuserdata thead' );
    $('#resetuserdata thead tr th').each( function (i) {
      var title = $(this).text();
      $(this).html( '<input type="text" placeholder="'+title+' suchen" />' );

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
