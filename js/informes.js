$( document ).ready(function() {


$('#excel_custodios').click(function(){
var url = '../lib/Reporte_excel.php?reporte_custodio';                 
   window.open(url, '_blank');
});

$('#excel_proyectos').click(function(){
var url = '../lib/Reporte_excel.php?reporte_proyecto';                 
   window.open(url, '_blank');
});

$('#excel_localizacion').click(function(){
var url = '../lib/Reporte_excel.php?reporte_emplazamiento';                 
   window.open(url, '_blank');
});

$('#excel_marcas').click(function(){
var url = '../lib/Reporte_excel.php?reporte_marca';                 
   window.open(url, '_blank');
});

$('#excel_estados').click(function(){
var url = '../lib/Reporte_excel.php?reporte_estado';                 
   window.open(url, '_blank');
});

$('#excel_generos').click(function(){
var url = '../lib/Reporte_excel.php?reporte_genero';                 
   window.open(url, '_blank');
});

$('#excel_colores').click(function(){
var url = '../lib/Reporte_excel.php?reporte_colores';                 
   window.open(url, '_blank');
});

$('#excel_clase_movimientos').click(function(){
var url = '../lib/Reporte_excel.php?reporte_clase_movimientos';                 
   window.open(url, '_blank');
});

$('#excel_movimientos_art').click(function(){
   var id =$('#txt_id').val();
    var desde = $('#txt_desde').val();    
    var hasta = $('#txt_hasta').val();
    var url = '../lib/Reporte_excel.php?reporte_movimientos_art=true&id='+id+'&desde='+desde+'&hasta='+hasta;                 
      window.open(url, '_blank');
 });

$('#excel_log').click(function(){

    action  = $('#txt_accion').val();
    fecha  = $('#txt_fecha').val();
    inte  = $('#txt_intento').val();
    estado  = $('input[name="rbl_estado"]:checked').val();

    if(action=='' && fecha=='' && inte=='' && estado=='' )
    {
       Swal.fire({
         title: 'Esta seguro de generar informe sin un filtro determinado?',
         text: "Este proceso podria tardar algunos minutos?",
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Si'
       }).then((result) => {
         if (result.value) {
            filtros = $('#form_filtros').serialize();
            var url = '../lib/Reporte_excel.php?reporte_log=true&'+filtros;                 
            window.open(url, '_blank');
         }
       })

    }else
    {
        filtros = $('#form_filtros').serialize();
        var url = '../lib/Reporte_excel.php?reporte_log=true&'+filtros;                 
        window.open(url, '_blank');
    } 
});


});


// function excel_marcas()
// {
//    var url = '../lib/Reporte_excel.php?reporte_marca';                 
//    window.open(url, '_blank');
// }