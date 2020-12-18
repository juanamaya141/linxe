// javascript inversionistas
jQuery(document).ready(function( $ ) {
    // toogle documentos
    //inicializa las grillas
            jQuery('.dataTables-example').DataTable({
                stateSave: true,
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'print'
                ],
                

                initComplete: function () {
                    this.api().columns().every( function () {
                        var column = this;
                        var select = $('<select class="filtersel"><option value="">Seleccione</option></select>')
                            .appendTo( $(column.header()).find(".filtro"))
                            .on( 'change', function () {
                                var val = $(this).val();

                                column
                                    .search( val ? ''+val+'' : '', true, false )
                                    .draw();
                            } );


                        var filter = column.search();
                        /*console.log("filter: "+filter);
                        console.log(column.search());
                        console.log("bbb ");
                        console.log(column);*/
                        column.data().unique().sort().each(function (d, j) {
                            var selected = (d === filter) ? 'selected' : '';
                            select.append('<option value="' + d + '" ' + selected + '>'+d+'</option>')
                        });
                    } );

                },
                "language": {
                    "sProcessing":     "Procesando...",
                    "sLengthMenu":     "Mostrar _MENU_ registros",
                    "sZeroRecords":    "No se encontraron resultados",
                    "sEmptyTable":     "Ningún dato disponible en esta tabla",
                    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":    "",
                    "sSearch":         "Buscar:",
                    "sUrl":            "",
                    "sInfoThousands":  ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":     "Último",
                        "sNext":     "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }

            });      
});


