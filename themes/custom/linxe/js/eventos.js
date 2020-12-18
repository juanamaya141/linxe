jQuery(document).ready(function(){
	//MENU FIXED down
	jQuery(window).scroll(function(){
		console.log("on scroll")
		if (jQuery(window).scrollTop() > 300)
		{
		    jQuery("header").addClass("add-header-fixed");
		    jQuery("#content").addClass("add-content");
		}
		else
		{
		    jQuery("header").removeClass("add-header-fixed");
		    jQuery("#content").removeClass("add-content");
		}
	});
	//MENU down fin
	 // slide principal
     jQuery('.slide-principal, .slider-movil').slick({
		dots: true,
		infinite: true,
		speed: 300,
		slidesToShow: 1,
		adaptiveHeight: true,
		autoplay: true,
		autoplaySpeed: 3000,
	});
    // slide principal fin

	// carrusel caracteristicas
    jQuery('.carrusel-banners').slick({
		dots: false,
		infinite: true,
		speed: 300,
		slidesToShow: 4,
		slidesToScroll: 4,
		autoplay: true,
		autoplaySpeed: 3000,
		// variableWidth: true,

		responsive: [
			{
			    breakpoint: 750,
			    settings: {
			    slidesToShow: 2,
			    slidesToScroll: 2
			    }
			},

			{
			    breakpoint: 420,
			    settings: {
			    slidesToShow: 1,
			    slidesToScroll: 1
			    }
			}
		]
	});
	// carrusel caracteristicas fin

	// carrusel caracteristicas
    jQuery('.carrusel-tabs, .carrusel-tabs2').slick({
		dots: false,
		infinite: true,
		speed: 300,
		slidesToShow: 4,
		slidesToScroll: 4,
		autoplay: true,
		autoplaySpeed: 3000,
		variableWidth: true,

		responsive: [
			{
			    breakpoint: 1024,
			    settings: {
			    slidesToShow: 2,
			    slidesToScroll: 2
			    }
			},
			{
			    breakpoint: 420,
			    settings: {
			    slidesToShow: 1,
			    slidesToScroll: 1
			    }
			}
		]
	});
	// carrusel caracteristicas fin

	// carrusel caracteristicas
    jQuery('.carrusel-requisitos').slick({
		dots: false,
		infinite: true,
		speed: 300,
		slidesToShow: 3,
		slidesToScroll: 3,
		autoplay: true,
		autoplaySpeed: 3000,

		responsive: [
			{
			    breakpoint: 750,
			    settings: {
			    slidesToShow: 2,
			    slidesToScroll: 2
			    }
			},
			{
			    breakpoint: 420,
			    settings: {
			    slidesToShow: 1,
			    slidesToScroll: 1
			    }
			}
		]
	});
	// carrusel caracteristicas fin


	// carrusel caracteristicas
    jQuery('.carrusel-aliados').slick({
		dots: false,
		infinite: true,
		speed: 300,
		slidesToShow: 6,
		slidesToScroll: 6,
		autoplay: true,
		autoplaySpeed: 3000,

		responsive: [
			{
			    breakpoint: 750,
			    settings: {
			    slidesToShow: 4,
			    slidesToScroll: 4
			    }
			},
			{
			    breakpoint: 600,
			    settings: {
			    slidesToShow: 2,
			    slidesToScroll: 2
			    }
			},
			{
			    breakpoint: 420,
			    settings: {
			    slidesToShow: 1,
			    slidesToScroll: 1
			    }
			}
		]
	});
	// carrusel caracteristicas fin

	


	// Slide toggle btn checkout agregar empresa
	jQuery( ".btn-agregar-empresa" ).click(function() {
	  	jQuery( ".datos-empresa" ).slideToggle( "slow" );
	});


	// Slide toggle btn checkout agregar empresa fin

	// Slide toggle btn buscador
	jQuery( ".btn-buscador-movil" ).click(function() {
	  	jQuery( ".buscador" ).slideToggle( "slow" );
	});
	// Slide toggle btn buscador

	// TABS checkout
	jQuery("body").on("click",".tabbers .tab",function(e){

		jQuery(".tabbers .tab").removeClass("tab-active");
		jQuery(this).addClass("tab-active");
			var tabTarg = jQuery(this).data("target");

		jQuery(".tabs-cont .tab-c").css("display", "none");
		jQuery("#"+tabTarg).css("display", "block");
	});
	// TABS checkout FIN

	//Js para mostrar boton top
	jQuery(window).scroll(function(){
		if (jQuery(window).scrollTop() > 100)
		    {
		        jQuery("header").addClass("add-header");
		        jQuery("#content").addClass("add-content");
		    }
		else
		    {
		        jQuery("header").removeClass("add-header");
		        jQuery("#content").removeClass("add-content");
		    }
	});
	//Js para mostrar boton top fin


	// ANIMACIÃ“N MENU ICONO
	var text = document.getElementById("text-menu");
	function toggleClass(element, className){
	    if (!element || !className){
	        return;
	    }

	    var classString = element.className;
	    var nameIndex = classString.indexOf(className);

	   
	    if (nameIndex == -1) {
	        classString += ' ' + className;
	    } else {
	        classString = classString.substr(0, nameIndex) + classString.substr(nameIndex+className.length);
	    }
	    element.className = classString;
	}

	/*
    document.getElementById('btn-menu-responsive').addEventListener('click', function() {
        toggleClass(this, 'close');
        text.innerHTML == "" ? text.innerHTML = "" : text.innerHTML = "";
    });

	// ANIMACIÃ“N MENU ICONO FIN

	// MENU RESPONSIVE

		var accion ="dentro";
	    jQuery("#btn-menu-responsive").click(function(){
		    if (accion == "dentro"){
		        jQuery(".cont-menu").addClass("add-menu");
		        accion = "fuera";
		    }
		    else{
		        jQuery(".cont-menu").removeClass("add-menu");
		        accion = "dentro";
		    }
	    });
	// MENU RESPONSIVE FIN
	*/
	

	jQuery( ".cerrar-modal" ).click(function() {
	  	jQuery( ".modal-linxe" ).css("display", "none");
	});

	jQuery( ".show-modal-webinar" ).click(function() {
	  	jQuery( ".modal-linxe" ).css("display", "block");
	});
	

	countdown(fechahora)
});

// Creamos las variables para obtener la canitdad correspondiente a cada una.
const getRemainTime = deadline => {
    let now = new Date(),
    remainTime = (new Date(deadline) - now + 1000) / 1000,
    remainSeconds = ('0' + Math.floor(remainTime % 60)).slice(-2),
    remainMinutes = ('0' + Math.floor(remainTime / 60 % 60)).slice(-2),
    remainHours = ('0' + Math.floor(remainTime / 3600 % 24)).slice(-2),
    remainDays = Math.floor(remainTime / (3600 * 24));
    // Las retornamos a dichas variables
    return {
        remainTime,
		remainSeconds,
		remainMinutes,
		remainHours,
		remainDays
    }
};
// Creamos el contador
const countdown = (deadline) => {
    // Aqui la variable "elem" es donde vamos a guardar el contenido del contador el cual al final de todo en countdown se le pone el verdadero id "clock" del HTML.
    //console.log(deadline)
    const timerUpdate = setInterval( () => {

        let t = getRemainTime(deadline);
        // Aqui insertamos las variables en el <h3 id="clock"></h3>
        //el.innerHTML = `${t.remainDays}d:${t.remainHours}h:${t.remainMinutes}m:${t.remainSeconds}s`;
        jQuery( "#dias_class" ).html(t.remainDays);
        jQuery( "#horas_class" ).html(t.remainHours);
        jQuery( "#minutos_class" ).html(t.remainMinutes);
        // Aca limpiamos el contador cuando llega a cero
        if (t.remainTime <= 1) {
            clearInterval(timerUpdate);
        }
    }, 1000);
};

function closeppp(){
	jQuery( ".modal-linxe" ).css("display", "none");
}

function mostrarpp(){
	jQuery( ".modal-linxe" ).css("display", "block");
}

