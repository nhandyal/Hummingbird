function changeCanvas(e,t){$(e).fadeOut(500,function(){$(e).removeClass("current");$(t).fadeIn(700,function(){$(t).addClass("current")})})}function initializePage(){var e=($(window).height()-150)/2;var t=e;var n=$(window).height()/2;$("#main-content-container").css({"padding-top":t});$("#logo-container").css({"padding-bottom":e});$("#works-content-wrapper .current").fadeIn(200)}$("document").ready(function(){$.browser.chrome=$.browser.webkit&&!!window.chrome;$.browser.safari=$.browser.webkit&&!window.chrome;if(!($.browser.chrome==true||$.browser.mozilla==true)){var e="<div style='width:300px; text-align:center; color:rgb(205,20,20)'>This site is best viewed in Google Chrome 20.0+ or Mozilla Firefox 13.0+.</div>";$.fancybox({overlayOpacity:.7,content:e})}initializePage();$("#ui-controls-fbCollapse").click(function(){$("#fb-like").animate({height:0},450,function(){})});$("#logo-container").mouseover(function(){$("#login-wrapper").animate({opacity:1},700,function(){})});$(".ui-menu-scrollable").click(function(){var e="";switch($(this).text()){case"About":e="#about-container";break;case"Register":e="#registration-master-container";break;case"Contact":e="#contact-container";break}var t=$(e).position().top-20;$("html, body").animate({scrollTop:t},650,function(){});return false});$("#login-link").click(function(){$("#login-link").css({display:"none"});$("#login-wrapper").css({display:"block"});return false});$("#next").click(function(){var e="#";var t=$("#works-content-wrapper .current").attr("id");if(t==4)e+=0;else e+=parseInt(t,10)+1;t="#"+parseInt(t,10);changeCanvas(t,e)});$("#prev").click(function(){var e="#";var t=$("#works-content-wrapper .current").attr("id");if(t==0)e+=4;else e+=parseInt(t,10)-1;t="#"+parseInt(t,10);changeCanvas(t,e)});$("#pwd").blur(function(){if($("#pwd").val().length<6)$("#pwd-error").css({opacity:1});else $("#pwd-error").css({opacity:0})});$("#confirm-pwd").blur(function(){var e=$("#confirm-pwd").val();if(e!=$("#pwd").val())$("#confirm-pwd-error").css({opacity:1});else $("#confirm-pwd-error").css({opacity:0})});$("#registration-form").submit(function(){if($("#confirm-pwd").val()==$("#pwd").val()&&$("#pwd").val()!=""&&$("#pwd").val().length>=6)return true;else if($("#confirm-pwd").val()!=$("#pwd").val()&&$("#pwd").val()!=""){$("#confirm-pwd-error").css({opacity:1})}return false});$("#contact-form").submit(function(){$.post("contact-support.php",{type:$("#type").val(),name:$("#form-name").val(),email:$("#form-email").val(),message:$("#form-textarea").val()},function(e){var t=JSON.parse(e);if(t.status==0){$("#form-container").fadeOut(700,function(){$("#form-container").replaceWith(t.message);$("#serv-response").animate({opacity:1},500,function(){})})}});return false})})