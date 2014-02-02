function display_alert(type, content) {
  $("#alert").stop().slideUp("fast", function() {
    $(this).attr("class", "alert alert-" + type).text(content).slideDown("fast", function() {
      $(this).delay(3000).slideUp("fast");
    });
  });
}
