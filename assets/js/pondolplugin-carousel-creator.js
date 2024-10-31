/** Pondolplugin Carousel - WordPress Image Carousel Plugin
 * Copyright 2014 pondol All Rights Reserved
 * Website: http://www.shop-wiz.com
 * Version 1.0
 */

var _dataIndex;
            	
(function ($) {
    $(document).ready(function () {
        $("#pondolplugin-carousel-toolbar").find("li").each(function (index) {
            $(this).click(function () {
                if ($(this).hasClass("pondolplugin-tab-buttons-selected")) return;
                $(this).parent().find("li").removeClass("pondolplugin-tab-buttons-selected");
                if (!$(this).hasClass("laststep")) $(this).addClass("pondolplugin-tab-buttons-selected");
                $("#pondolplugin-carousel-tabs").children("li").removeClass("pondolplugin-tab-selected");
                $("#pondolplugin-carousel-tabs").children("li").eq(index).addClass("pondolplugin-tab-selected");
                $("#pondolplugin-carousel-tabs").removeClass("pondolplugin-tabs-grey");
            })
        });
        
        //save current carousel
        $(".btn_save_pondolplugin_carousel").click(function(){
         	publishCarousel();
        });
        
        var getURLParams = function (href) {
            var result = {};
            if (href.indexOf("?") < 0) return result;
            var params = href.substring(href.indexOf("?") + 1).split("&");
            for (var i = 0; i < params.length; i++) {
                var value = params[i].split("=");
                if (value && value.length == 2 && value[0].toLowerCase() != "v") result[value[0].toLowerCase()] = value[1]
            }
            return result
        };
        var load_dialog = function(extraStuff) {
            return function(html, textStatus) {           	
            	var dialogType	= extraStuff.extraStuff;
            	var onSuccess	= extraStuff.onSuccess;
            	var data	= extraStuff.data;
            	var dataIndex	= extraStuff.dataIndex;
            	
            	$("body").append(html);
            };
        };

        var slideDialog = function ( dataIndex) {
        	_dataIndex	= dataIndex;

        	$.post(PONDOLPLUGIN_CAROUSEL_URL+"pages/admin_add_image_dialog.php", {}, function(data){
        		$("body").append(data);
        	});
        }; 
        
        
        updateMediaTable = function () {
            $("#pondolplugin-carousel-media-table tbody").empty();
            for (var i = 0; i < config.slides.length; i++) {
            	var str = "<tr>";
            	str = str +"<td>" + (i + 1) + "</td>";
            	str = str +"<td><img src='" + config.slides[i].thumbnail +"' style='width:60px;height:60px;' /></td>";
            	str = str +"<td>";
            	str = str +"<input type='button' class='button pondolplugin-carousel-media-table-edit' value='Edit' />";
            	str = str +"<input type='button' class='button pondolplugin-carousel-media-table-moveup' value='Move Up' />";
            	str = str +"<input type='button' class='button pondolplugin-carousel-media-table-movedown' value='Move Down' />";
            	str = str +"<input type='button' class='button pondolplugin-carousel-media-table-delete' value='Delete' />";
            	str = str +"</td>";
            	str = str +"</tr>";
            	$("#pondolplugin-carousel-media-table tbody").append(str)
            }
        };
        
       
        $("#pondolplugin-add-image").click(function () {
        	slideDialog(-1);
        });


        $(document).on("click", ".pondolplugin-carousel-media-table-edit", function () {
            var index = $(this).parent().parent().index();
            slideDialog(index);
        });

        
        $(document).on("click", ".pondolplugin-carousel-media-table-delete",
            function () {
                var $tr = $(this).parent().parent();
                var index = $tr.index();
                config.slides.splice(index, 1);
                $tr.remove();
                $("#pondolplugin-carousel-media-table tbody").find("tr").each(function (index) {
                    $(this).find("td").eq(0).text(index + 1)
                })
            });
        
        $(document).on("click", ".pondolplugin-carousel-media-table-moveup", function () {
            var $tr = $(this).parent().parent();
            var index = $tr.index();
            if (index == 0) return;
            var data = config.slides[index];
            config.slides.splice(index,
                1);
            config.slides.splice(index - 1, 0, data);
            var $prev = $tr.prev();
            $tr.remove();
            $prev.before($tr);
            $("#pondolplugin-carousel-media-table tbody").find("tr").each(function (index) {
                $(this).find("td").eq(0).text(index + 1)
            })
        });
        
        $(document).on("click", ".pondolplugin-carousel-media-table-movedown", function () {
            var $tr = $(this).parent().parent();
            var index = $tr.index();
            if (index == config.slides.length - 1) return;
            var data = config.slides[index];
            config.slides.splice(index,
                1);
            config.slides.splice(index + 1, 0, data);
            var $next = $tr.next();
            $tr.remove();
            $next.after($tr);
            $("#pondolplugin-carousel-media-table tbody").find("tr").each(function (index) {
                $(this).find("td").eq(0).text(index + 1)
            })
        });
      

        $("input:radio[name=pondolplugin-carousel-skin]").click(function () {
            if ($(this).val() == pondolplugin_carousel_config.skin) return;
            $(".pondolplugin-tab-skin").find("img").removeClass("selected");
            $("input:radio[name=pondolplugin-carousel-skin]:checked").parent().find("img").addClass("selected");
            pondolplugin_carousel_config.skin = $(this).val();

        });
        $(".pondolplugin-carousel-options-menu-item").each(function (index) {
            $(this).click(function () {
                if ($(this).hasClass("pondolplugin-carousel-options-menu-item-selected")) return;
                $(".pondolplugin-carousel-options-menu-item").removeClass("pondolplugin-carousel-options-menu-item-selected");
                $(this).addClass("pondolplugin-carousel-options-menu-item-selected");
                $(".pondolplugin-carousel-options-tab").removeClass("pondolplugin-carousel-options-tab-selected");
                $(".pondolplugin-carousel-options-tab").eq(index).addClass("pondolplugin-carousel-options-tab-selected")
            })
        });
        
        var updateCarouselOptions = function () {
        	config.name			= $.trim($("#pondolplugin-carousel-name").val());
        	config.skin			= $("input:radio[name=pondolplugin-carousel-skin]:checked").val();
        	config.width		= parseInt($.trim($("#pondolplugin-carousel-width").val()));
        	config.height		= parseInt($.trim($("#pondolplugin-carousel-height").val()));
        	config.autoplay		= $("#pondolplugin-carousel-autoplay").is(":checked");
        	config.random		= $("#pondolplugin-carousel-random").is(":checked");
        	config.speed		= parseInt($.trim($("#pondolplugin-carousel-speed").val()));
        	config.direction	= $.trim($("#pondolplugin-carousel-direction").val());
        	config.visibleitems	= parseInt($.trim($("#pondolplugin-carousel-visibleitems").val()));
        };

        //save carousel
        var publishCarousel = function () {
            updateCarouselOptions();
            config.id	= itemid;
            jQuery.ajax({
                url: ajaxurl,
                type: "POST",
                data: {
                    action: "pondolplugin_carousel_save_item",
                    item: config
                  },
                success: function (data) {
                    if (data.success && data.id >= 0) {
                        config.id = data.id;
                        alert('saved..');
                    } else{
                    	alert('fail');
                    }
                }
            })   
        };
        
    })
})(jQuery);