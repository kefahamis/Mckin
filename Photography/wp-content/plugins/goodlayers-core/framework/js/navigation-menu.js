!function(t){"use strict";t(document).ready(function(){t("#menu-management").on("input change","[data-slug]",function(){var n=t(this).closest(".gdlr-core-custom-nav-menu-fields"),a={};n.find("[data-slug]").each(function(){t(this).is('input[type="checkbox"]')?a[t(this).attr("data-slug")]=t(this).is(":checked")?"enable":"disable":a[t(this).attr("data-slug")]=t(this).val()}),n.children(".gdlr-core-custom-nav-menu-val").val(JSON.stringify(a))})})}(jQuery);