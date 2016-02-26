(function(window, $){         
    function scrollTo(target) {
        var wheight = $(window).height() / 2;
        var alto = $("#header-top").height() + $("#header").height();
        
        var ooo = $(target).offset().top - alto;
        $('html, body').animate({scrollTop:ooo}, 600);
    }
    
    function ajustarVisibilidadMenu(){        
        if (!$("#top-menu-resp").is(":visible")){
            var docViewTop = $(window).scrollTop();
            var docViewBottom = docViewTop + ($(window).height());
            var elemTop = $("#header-top").offset().top;
            var elemBottom = elemTop + $("#header-top").height();
            if ($("#header-top").height() <  docViewBottom && docViewTop > 0){
                $("#header-top").addClass("header_fijo");
                $("#mini-logo").removeClass("hide");
                $("body").css("padding-top", $("#header-top").height());
                $("#header").addClass("hide");
            }
            else{
                $("#mini-logo").addClass("hide");
                $("#header").removeClass("hide");
                $("#header-top").removeClass("header_fijo");
                $("body").css("padding-top", 0);
            }
        }
        else{
            $("#header-top").removeClass("header_fijo");
            $("#header").show("slow");
        }
    }
    
    function fadear(){
        /*var docViewTop = $(window).scrollTop();
        var docViewBottom = docViewTop + ($(window).height() / 2);
        $("[rel='fade']").each(function (i, o){
            var elemTop = $(o).offset().top;
            var elemBottom = elemTop + $(o).height();
            console.log($(o));
            console.log($(o).offset().top);
            console.log(docViewTop);
            if (elemTop < docViewTop){                        
                $(o).fadeIn(1000);                
            }
        });*/
    }    
    
    $(document).ready(function() {
        $(window).resize(function() {
            if ($("#top-menu-resp").is(":visible")){
                $("#header-top ul:first-child").hide();
            }
            else{                    
                $("#header-top ul:first-child").show();
            }
        });
        
        $("#top-menu-resp > a").click(function(e){
            e.preventDefault();
            $("#header-top ul:first-child").toggle("slow");
        });
        
        $("a[rel='scroll']").click(function(e){
            e.preventDefault();            
            scrollTo($($(this).attr("href")));
        });
        
        if ($("#banner_home").length){
            var $sliderHome = $("#banner_home").lightSlider({
                adaptiveHeight:true,
                item:1,
                slideMargin:0,
                pager: true,
                enableDrag: true,
                controls: true,
                loop: true,
                auto: true,
                pause: 8000
            });
                    
            $sliderHome.refresh();
            //$sliderHome.play();
        }

        if ($("#slider_ipad").length){
            var $sliderIpad = $("#slider_ipad").lightSlider({
                adaptiveHeight:true,
                item:1,
                slideMargin:0,
                pager: false,
                enableDrag: true,
                controls: false,
                loop: true,
                mode: 'fade',
                auto: true,
                pause: 5000
            });

            $sliderIpad.refresh();
            //$sliderIpad.play();
        }
        
        $("#btnEnviarSol").click(function(e) {
            e.preventDefault();
            
            if ($("#nombre_contacto").val() === ""){
                alert('Debe ingresar su nombre');
                return;
            }
            
            if ($("#email_contacto").val() === ""){
                alert('Debe ingresar su dirección de correo');
                return;
            }
            
            var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
            if (!re.test($("#email_contacto").val())){
                alert('Debe ingresar una dirección de correo válida');
                return;
            }
            
            if ($("#mensaje_contacto").val() === ""){
                alert('Debe ingresar un mensaje');
                return;
            }
            
            $("#loader").addClass("loading");
            
            $.ajax({                
                url: $("#formContacto").attr("action"),
                method: 'post',
                data: $("#formContacto").serialize(),
                success: function(res){
                    $("#loader").removeClass("loading");
                    $("#nombre_contacto").val('');
                    $("#email_contacto").val('');
                    $("#mensaje_contacto").val('');
                    alert(res);
                }
                
            });
        });
        
        $("#btnSuscripcion").click(function(e) {
            e.preventDefault();
            
            if ($("#email_news").val() === ""){
                alert('Debe ingresar su dirección de correo');
                return;
            }
            
            var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
            if (!re.test($("#email_news").val())){
                alert('Debe ingresar una dirección de correo válida');
                return;
            }
            
            $("#loader").addClass("loading");
            
            $.ajax({                
                url: $(this).attr("href"),
                method: 'post',
                data: { email : $("#email_news").val() },
                success: function(res){
                    $("#loader").removeClass("loading");
                    $("#email_news").val('');
                    alert(res);
                }
                
            });
        });        
        
        Foundation.global.namespace = '';
        $(document).foundation();
    });
    
    $(window).load(function() {
        ajustarVisibilidadMenu();
        fadear();
        
        $(window).scroll(function () {
            ajustarVisibilidadMenu();
            fadear();
        });
        
        if ($(".galeria").length){
            $.each($(".galeria ul"), function(i, o) {
                var len = 0;
                $.each($(o).children("li"), function(i, o) {
                    len += $(o).innerWidth() + 40;                    
                });
                
                $(o).width(len);
            });
            
            /*$(".galeria").niceScroll({
                horizrailenabled: true,
                cursorwidth: 10,
                autohidemode: false
            });*/
            
            $('.galeria').jScrollPane({
                showArrows: true,
                arrowScrollOnHover: false
            });
        }
    });
})(window, jQuery);