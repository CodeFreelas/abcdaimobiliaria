(()=>{function e(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}var t=function(){function t(){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,t)}var n,r;return n=t,(r=[{key:"init",value:function(){$(document).on("click",".answer-trigger-button",(function(e){e.preventDefault(),e.stopPropagation();var t=$(".answer-wrapper");t.is(":visible")?t.fadeOut():t.fadeIn()})),$(document).on("click",".answer-send-button",(function(e){e.preventDefault(),e.stopPropagation(),$(e.currentTarget).addClass("button-loading");var t=$("#message").val();"undefined"!=typeof tinymce&&(t=tinymce.get("message").getContent()),$.ajax({type:"POST",cache:!1,url:route("contacts.reply",$("#input_contact_id").val()),data:{message:t},success:function(t){if(!t.error){if($(".answer-wrapper").fadeOut(),"undefined"!=typeof tinymce)tinymce.get("message").setContent("");else{$("#message").val("");var n=document.querySelector(".answer-wrapper .ck-editor__editable").ckeditorInstance;n&&n.setData("")}Srapid.showSuccess(t.message),$("#reply-wrapper").load(window.location.href+" #reply-wrapper > *")}$(e.currentTarget).removeClass("button-loading")},error:function(t){$(e.currentTarget).removeClass("button-loading"),Srapid.handleError(t)}})}))}}])&&e(n.prototype,r),Object.defineProperty(n,"prototype",{writable:!1}),t}();$(document).ready((function(){(new t).init()}))})();
