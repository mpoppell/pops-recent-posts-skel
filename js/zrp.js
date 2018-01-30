/**
 * Created by Pops on 11/05/2016.
 * Equalises the height of the various containers
 */

$(document).ready(
    function ($) {
      var leftColumnHeight = 0
      var rightColumnHeight = 0
      var items = $('.zonkey-recent-post')
      for (var i = 0; i < items.length; i++) {
        if (leftColumnHeight > rightColumnHeight) {
          rightColumnHeight += items.eq(i).addClass('right').outerHeight(true)
        } else {
          leftColumnHeight += items.eq(i).outerHeight(true)
        }
      }
    })
