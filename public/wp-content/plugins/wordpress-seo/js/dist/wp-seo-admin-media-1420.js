(window.yoastWebpackJsonp=window.yoastWebpackJsonp||[]).push([[13],{439:function(e,t,a){"use strict";jQuery(document).ready(function(e){void 0!==wp.media&&e(".wpseo_image_upload_button").each(function(t,a){var n=function(t){var a=(t=e(t)).data("target");return a&&""!==a||(a=e(t).attr("id").replace(/_button$/,"")),a}(a),i=function(t){return(t=e(t)).data("target-id")}(a),o=e("#"+n),r=e("#"+i),u=wp.media.frames.file_frame=wp.media({title:wpseoMediaL10n.choose_image,button:{text:wpseoMediaL10n.choose_image},multiple:!1,library:{type:"image"}});u.on("select",function(){var e=u.state().get("selection").first().toJSON();o.val(e.url),r.val(e.id)});var c=e(a);c.click(function(e){e.preventDefault(),u.open()}),c.siblings(".wpseo_image_remove_button").on("click",function(e){e.preventDefault(),o.val(""),r.val("")})})})}},[[439,0]]]);