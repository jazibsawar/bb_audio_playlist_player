(function ($) {

	'use strict';
	$(function () {

		var playlist_count = $("#playlist_count");

		if( playlist_count.length ) {
			var index = parseInt(playlist_count.val());
			var el = document.getElementById('playlist-items');

			var playlists = $("#playlist-items");

			var sortable = Sortable.create(el, {
				handle: '.dashicons-move',
				animation: 150,
				onSort: function (evt) {
					fixListIndexed();
				}
			});
	
			$("#add_song").click(function () {
				$("#playlist-item-empty").remove();
				var html = '<li class="playlist-item playlist-item-new-'+index+'"><table class="accordion-content-header"><tr>';
				html += '<td class="handle"><span class="dashicons dashicons-move"></span></td>';
				html += '<td class="input name"><div class="input_label">Name: </div><div class="input_field">';
				html += '<input type="text" name="playlist['+index+'][name]" value="" placeholder="Name:">';
				html += '</div></td>';
				html += '<td class="icon-right remove"><span class="dashicons dashicons-trash" title="Remove"></span></td>';
				html += '<td class="icon-right duplicate"><span class="dashicons dashicons-admin-page" title="Duplicate"></span></td>';
				html += '<td class="icon-right"><span class="dashicons dashicons-arrow-down accordion-toggle" title="Toggle"></span></td>';
				html += '</tr></table>';
				html += '<div class="accordion-content default"><br>';
				html += '<div><div class="input_label">Author: </div><div class="input_field author">';
				html += '<input type="text" name="playlist['+index+'][author]" value="" placeholder="Author:" >';
				html += '</div></div>';
				html += '<div><div class="input_label">URL: </div><div class="input_field url">';
				html += '<input type="text" name="playlist['+index+'][url]" value="" placeholder="Url:">';
				html += '</div></div></div>';
				html += '</li>';

				playlists.find('.accordion-content').slideUp('fast');

				playlists.append(html);

				addRemoveListener(".playlist-item-new-"+index+" .remove");
				addDuplicateListener(".playlist-item-new-"+index+" .duplicate");
				accordianHandler(".playlist-item-new-"+index+" .accordion-toggle");
				fixListIndexed();

				index = index + 1;
			});

			$("#publish").click(function(){
				var valid_input = true;
				var valid_url = true;
				$("td.input input").filter(function () {
					if ($.trim($(this).val()).length == 0 )
					{
						$(this).addClass("invalid invalid-input");
						valid_input = false;
					}
				});

				$("td.url input").filter(function () {
		
					if(!is_valid_url($(this).val())){
						$(this).addClass("invalid invalid-url");
						valid_url = false;
					}
				});

				if(valid_input && valid_url)
					return true;
				return false;
			});

			function is_valid_url(url) {
				if(/^http(s)?:\/\/(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/.test(url))
				{
					if(url.match(".mp3$") || url.match(".MP3$")){
						return true;
					}
				}
				return false;
			}

			function fixListIndexed() {
				playlists.find("li.playlist-item").each(function(index){
					$(this).find(".name input").first().attr("name", "playlist["+index+"][name]");
					$(this).find(".author input").first().attr("name", "playlist["+index+"][author]");
					$(this).find(".url input").first().attr("name", "playlist["+index+"][url]");
				});
			}

			function deleteItem(_el) {
				$(_el).closest(".playlist-item").fadeOut(300,function(){
					$(_el).closest(".playlist-item").remove();
					index = index - 1;
					fixListIndexed();
				});
			}

			function duplicateItem(_el) {
				var playlist = $(_el).closest(".playlist-item");
				var temp = playlist.clone(true);
				playlists.find('.accordion-content').slideUp('fast');
				playlist.after(temp);
				temp.find('.accordion-content').first().slideDown('fast');
				index = index + 1;
				fixListIndexed();
			}

			function addRemoveListener(target) {
				$(target).click(function(e){
					deleteItem(e.target);
				});
			}

			function addDuplicateListener(target) {
				$(target).click(function(e){
					duplicateItem(e.target);
				});
			}

			function accordianHandler(target) {
				$(target).click(function(){
					var header = $(this).closest('.accordion-content-header');
					header.next().slideToggle('fast');
					$(".accordion-content").not(header.next()).slideUp('fast');
				});
			}

			addRemoveListener(".playlist-items .remove");
			addDuplicateListener(".playlist-items .duplicate");
			accordianHandler(".playlist-items .accordion-toggle");
		}
	
	});
})(jQuery);