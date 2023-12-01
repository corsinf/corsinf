$(document).ready(function(){

 $('#txt_logo').change(function() {
                mostrarVistaPrevia(this);
    });


var current_fs, next_fs, previous_fs; //fieldsets
var opacity;
var current = 1;
var steps = $("fieldset").length;

setProgressBar(current);

$(".next").click(function(){

	current_fs = $(this).parent();
	next_fs = $(this).parent().next();

	var emp = $('#txt_empresa').val();
	var ci = $('#txt_ci').val();
	var ema = $('#txt_email').val();
	var tel = $('#txt_telefono').val();
	var dir = $('#txt_direccion').val();
	if(emp=='' || ci=='' || ema=='' || tel=='' || dir=='')
	{
		Swal.fire('Llene todo los campos','','info')
		return false;
	}


	//Add Class Active
	$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

	//show the next fieldset
	next_fs.show();
	//hide the current fieldset with style
	current_fs.animate({opacity: 0}, {
	step: function(now) {
	// for making fielset appear animation
	opacity = 1 - now;

	current_fs.css({
	'display': 'none',
	'position': 'relative'
	});
	next_fs.css({'opacity': opacity});
	},
	duration: 500
	});
	setProgressBar(++current);
});

$(".next2").click(function(){

	var db = $('#txt_base').val();
	var ip = $('#txt_ip').val();
	var pue = $('#txt_puerto').val();
	var usu= $('#txt_usuario_db').val();
	var pass = $('#txt_pass_db').val();
	if(db=='' ||  ip=='' ||  pue=='' ||  usu=='' ||  pass=='')
	{
		Swal.fire('Llene todo los datos','','info')
		return false;
	}

	current_fs = $(this).parent();
	next_fs = $(this).parent().next();

	//Add Class Active
	$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

	//show the next fieldset
	next_fs.show();
	//hide the current fieldset with style
	current_fs.animate({opacity: 0}, {
	step: function(now) {
	// for making fielset appear animation
	opacity = 1 - now;

	current_fs.css({
	'display': 'none',
	'position': 'relative'
	});
	next_fs.css({'opacity': opacity});
	},
	duration: 500
	});
	setProgressBar(++current);
});

$(".next3").click(function(){

	var host = $('#txt_host').val();
	var smtp_puerto = $('#txt_puerto_smtp').val();
	var smtp_secure = $('#txt_secure').val();
	var smtp_usu = $('#txt_usuario_smtp').val();
	var smtp_pass = $('#txt_pass_smtp').val();
	if(host =='' || smtp_puerto =='' || smtp_secure  =='' || smtp_usu =='' || smtp_pass =='')
	{
		Swal.fire('Llene todo los datos','','info')
		return false;
	}

	current_fs = $(this).parent();
	next_fs = $(this).parent().next();

	//Add Class Active
	$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

	//show the next fieldset
	next_fs.show();
	//hide the current fieldset with style
	current_fs.animate({opacity: 0}, {
	step: function(now) {
	// for making fielset appear animation
	opacity = 1 - now;

	current_fs.css({
	'display': 'none',
	'position': 'relative'
	});
	next_fs.css({'opacity': opacity});
	},
	duration: 500
	});
	setProgressBar(++current);
});

$(".previous").click(function(){

current_fs = $(this).parent();
previous_fs = $(this).parent().prev();

//Remove class active
$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

//show the previous fieldset
previous_fs.show();

//hide the current fieldset with style
current_fs.animate({opacity: 0}, {
step: function(now) {
// for making fielset appear animation
opacity = 1 - now;

current_fs.css({
'display': 'none',
'position': 'relative'
});
previous_fs.css({'opacity': opacity});
},
duration: 500
});
setProgressBar(--current);
});

function setProgressBar(curStep){
var percent = parseFloat(100 / steps) * curStep;
percent = percent.toFixed();
$(".progress-bar")
.css("width",percent+"%")
}


});

function smtp_config()
{
	selec = $('input[name="rbl_smtp_default"]:checked').val();
	if(selec=='si')
	{
		$('#txt_host').val('corsinf.com');
		$('#txt_puerto_smtp').val('465');
		$('#txt_ssl').prop('checked',true);
		$('#txt_usuario_smtp').val('soporte');
		$('#txt_pass_smtp').val('62839300');
	}else
	{
		$('#txt_host').val('');
		$('#txt_puerto_smtp').val('');
		$('#txt_secure').val('');
		$('#txt_usuario_smtp').val('');
		$('#txt_pass_smtp').val('');
	}

}

function mostrarVistaPrevia(input) {
            var vistaPrevia = $('#img_foto')[0];

            if (input.files && input.files[0]) {
                var lector = new FileReader();

                lector.onload = function(e) {
                    // Establecer la vista previa como la URL de datos de la imagen
                    vistaPrevia.src = e.target.result;
                }

                // Leer el archivo como una URL de datos
                lector.readAsDataURL(input.files[0]);
            }
        }