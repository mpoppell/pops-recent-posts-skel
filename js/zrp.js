/**
 * Created by Pops on 11/05/2016.
 */


$(document).ready(
    function($){

        var left_column_height = 0;
        var right_column_height = 0;
        var items = $('.zonkey-recent-post');
        for (var i = 0; i < items.length; i++) {
            

            if (left_column_height > right_column_height) {
                right_column_height+= items.eq(i).addClass('right').outerHeight(true);
            } else {
                left_column_height+= items.eq(i).outerHeight(true);

            }
        }



    });