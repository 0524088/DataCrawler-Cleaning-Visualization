
$(	function() {
  $(window).scroll(function() {
    if ($(this).scrollTop() > 5)  {          /* 要滑動到選單的距離 */
       $('.dropdown').addClass('navFixed');   /* 幫選單加上固定效果 */
    } else {
      $('.dropdown').removeClass('navFixed'); /* 移除選單固定效果 */
    }
  });
});