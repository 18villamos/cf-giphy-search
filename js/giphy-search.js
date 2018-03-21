var $j = jQuery.noConflict();

$j(document).ready(function(){
    (function($) {

        //this works for both the Search button and the pagination buttons.  The "data_offset" attribute of the buttons determines where we are in the pagination.
         $('.giphy-search-button').live("click", (function(event) {
            //event.preventDefault();

            var search_term         = $('#giphy_search_find_images').val();
            var result_limit        = 8;
            var result_offset       = $(this).attr("data_offset");

            giphy_search_results(search_term,result_limit,result_offset);

         }));

        //Display search results according to parameters.  GIFs are shown as static thumbnails, and animation can be viewed by mousing over.  This is to minimize visual chaos.
        //var giphy_key is defined in config.js, not included here.
        function giphy_search_results(search_term,result_limit,result_offset) {
            $('#giphy_results').empty();
            var search_url_string   = "q=" + search_term + "&api_key=" + giphy_key + "&limit=" + result_limit + "&offset=" + result_offset;
            var new_offset          = +result_limit + +result_offset;
            var previous_offset     = +result_offset - +result_limit;
            var xhr = $.get("http://api.giphy.com/v1/gifs/search?" + search_url_string);


            xhr.done(function(data) {

                var results     = "";
                var more_width  = "";
                $.each(data.data, function(k, v) {
                    image_url       = v.images.downsized_still.url;
                    image_width     = v.images.downsized_still.width;
                    image_height    = v.images.downsized_still.height;
                    image_animated  = v.images.downsized.url;
                    image_id        = v.id;
                    image_index     = result_offset+k;

                    results += '<img src="' + image_url + '" width="' + image_width + '" height="' + image_height + '" class="giphy-image" data_id="' + image_index + '" data_animated="' + image_animated + '" data_still="" data_giphy_id="' + image_id + '"/>';
                });

                var pagination_links = '<div class="pagination_links">';
                if (result_offset > 0) {
                    pagination_links += '<div class="previous_results"><a class="button button-primary button-small giphy-search-button" id="previous_results_button" data_offset="' + previous_offset + '">&larr; Previoius Results</a></div>';
                } else {
                    more_width        = 'style="width: 100%"';
                }


                pagination_links += '<div class="more_results" '  + more_width + '><a class="button button-primary button-small giphy-search-button" id="more_results_button" data_offset="' + new_offset + '">More Results &rarr;</a></div><div style="clear:both;">'
                pagination_links += '</div>';

                $('#giphy_results').append("<p>Mouse over the images to see the animation. Click an image to add it to this post. Commit by clicking Update.</p>");
                $('#giphy_results').append(results);
                $('#giphy_results').append(pagination_links);
            });
         }

         //When you mouse over an image in the results, it will change from still to animated and a border will appear around it.
         $('.giphy-image').live("mouseenter", function() {
            var new_src     = $(this).attr("data_animated");
            var original    = $(this).attr("src");
            $(this).addClass('current-image');
            $(this).attr("src", new_src);
            $(this).attr("data_still", original);
         });

         $('.giphy-image').live("mouseleave", function() {
            $(this).removeClass('current-image');
            var original    = $(this).attr("data_still");
            $(this).attr("src", original);
         });

         //clicking an image will add a copy above in the Chosen GIFs box and create a new hidden field with the image's Giphy ID to add to the array that will populate the giphy_images meta field.
         $('.giphy-image').live("click", function() {
            var src                 = $(this).attr("data_animated");
            var image_id            = $(this).attr("data_giphy_id");
            var chosen_images       = "";

            $(this).addClass('selected-image');

            chosen_images += '<img src="' + src + '" class="giphy-chosen-image" data_image_id="' + image_id + '" />';

            chosen_images_hidden_field = '<input type="hidden" name="chosen_images[]" value="' + image_id + '"/>';

            $('#giphy_chosen_images h2').css("display","block");
            $('#giphy_chosen_images').append(chosen_images);
            $('#giphy_chosen_images').append(chosen_images_hidden_field);

         });

         //Clicking an image in the Chosen GIFs box will flag it for deletion when the post is updated.  Both the thumbnail and the corresponding hidden field are removed here.
         $('.giphy-chosen-image').live("click", function() {

            if (confirm("Continuing will delete this GIF?")) {
                var image_id = $(this).attr("data_image_id");
                $(this).remove();
                $("input").filter(function(){return this.value==image_id}).remove();
            }
         });

    })(jQuery);
});
