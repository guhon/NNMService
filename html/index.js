$(function(){
  $("ul.tab li:first a").addClass("selected");
  $("ul.panel li:not(:first)").hide();
  $("ul.tab a").click(function(){
    if(!$(this).hasClass("selected")){
      $("ul.tab a.selected").removeClass("selected");
      $(this).addClass("selected");
      $("ul.panel li").hide().filter($(this).attr("href")).show();
    }
    return false;
  })
}
