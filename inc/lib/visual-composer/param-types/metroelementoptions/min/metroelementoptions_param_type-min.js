!function($,e,i){$(document).ready(function(){function e(){var e=d.val(),i=f.filter("#me_box_widgets_"+e);r.hide(),f.hide(),i.show()}function t(){var e="";return l.find(".me_field:visible").each(function(i,t){var n=$(t),a=n.attr("name"),d=n.val();n.is('[type="checkbox"]')&&(d=n.is(":checked")?1:0),e+=a+"="+d+"&"}),e.length>0&&(e=e.substr(0,e.length-1)),o.val(e),e}function n(){var e={},t=o.val().split("&");if(t&&t.length>0)for(var n in t){var a=t[n].split("="),d=a[0],r=a[1];d!=i&&(e[d]=r)}for(var f in e){var c=l.find('.me_field[name="'+f+'"]:visible'),r=e[f];c.is("select")?c.find("option").attr("selected",!1).filter('[value="'+r+'"]').attr("selected",!0):c.is('input[type="checkbox"]')&&c.attr("checked",1==r)}}var a=$(".wpb-element-edit-modal"),d=a.find('select[name="metroelement_size"]'),l=a.find("#metroelement_box_type_options"),o=a.find('input[name="selected_widgets"]');if(d.length){var r=l.find(".loading_options"),f=a.find(".me_box_widgets");e(),n(),t(),d.change(e),l.find(".me_field").change(t)}})}(jQuery,window);