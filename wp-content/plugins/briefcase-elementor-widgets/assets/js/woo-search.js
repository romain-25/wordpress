'use strict';

var woosearch;

(
	function() {

		woosearch = (
		function() {

				return {

					init: function() {

						this.search();
					}
				}
			}()
		);
	}
)( jQuery );

jQuery( document ).ready( function() {
	woosearch.init();
} );
	
// Search
(
	function( $ ) {

		var $body = $( 'body' );
						
		woosearch.search = function() {
			var $search      = $( '.header-search' ),
				$formWrapper = $( '.search-form-wrapper' ),
				$closeBtn    = $( '.btn-search-close' ),
				$form        = $( 'form.ajax-search-form' ),
				$select      = $form.find( 'select.search-select' ),
				$input       = $form.find( 'input.search-input' ),
				$ajaxNotice  = $( '.ajax-search-notice' ),
				noticeText   = $ajaxNotice.text(),
				found        = false;
			
			// add unique id
			$('.bew-woo-search-container').each(function(i){
			  this.id= 'bew-search-' + i;
			  $(this).find('.search-results-wrapper').addClass('bew-search-' + i)
			});
				
			
			if ( document.querySelector( '.bew-woo-search' ) === null ) {
			$body.addClass( 'hide-bew-woo-search' );
			return;
			}
			
			if ( ! $search.length ) {
				return;
			}

			var categoriesSelectBox = function() {

				if ( $select.find( '>option' ).length ) {
					$select.select2( {
						templateResult: function( str ) {

							if ( ! str.id ) {
								return str.text;
							}

							return $( '<span>' + str.text + '</span>' );
						},
					} ).on( 'change', function() {

						var text = $( this )
							.find( 'option[value="' + $( this ).val() + '"]' )
							.text()
							.trim();

						$( '#select2-product_cat-container' ).text( text );
						$( '#select2-cat-container' ).text( text );

						setTimeout( function() {
							$input.focus();
						}, 500 );
						
						$( ".autocomplete-suggestion" ).remove();
						ajaxSearch();
					} );

					$select.next( '.select2' ).on( 'mousedown', function() {
						$( '#select2-product_cat-results' ).perfectScrollbar();
					} );
				}
			};

			var events = function() {

				$search.on( 'click', '> .toggle', function( e ) {

					e.preventDefault();

					openSearch();
				} );

				$search.on( 'focus', 'input.fake-input', function( e ) {

					e.preventDefault();

					openSearch();
				} );

				$closeBtn.on( 'click', function() {
					closeSearch();
				} );

				$input.on( 'keyup', function( event ) {

					if ( event.altKey || event.ctrlKey || event.shiftKey || event.metaKey ) {
						return;
					}
					var keys = [9, 16, 17, 18, 19, 20, 33, 34, 35, 36, 37, 39, 45, 46];

					if ( keys.indexOf( event.keyCode ) != - 1 ) {
						return;
					}

					switch ( event.which ) {
						case 8: // backspace
							if ( $( this ).val().length < woosearchConfigs.search_min_chars ) {
								$( '.autocomplete-suggestion' ).remove();
								$( '.search-view-all' ).remove();
								$ajaxNotice.fadeIn( 200 ).text( noticeText ).removeClass('has-notice');
							}
							break;
						case 27:// escape

							// close search
							if ( $( this ).val() == '' ) {
								closeSearch();
							}

							// remove result
							$( '.autocomplete-suggestion' ).remove();
							$( '.search-view-all' ).remove();
							$( this ).val( '' );

							$ajaxNotice.fadeIn( 200 ).text( noticeText ).addClass('has-notice');

							break;
						default:
							break;
					}
				} );
			};

			var ajaxSearch = function() {
				
				var productCat = '0',
					cat        = '0',					
					symbol     = woosearchConfigs.ajax_url.split( '?' )[1] ? '&' : '?',
					postType   = $form.find( 'input[name="post_type"]' ).val(),
					url        = woosearchConfigs.ajax_url + symbol + 'action=bew_ajax_search';

				if ( $select.find( 'option' ).length ) {
					productCat = cat = $select.val();
				}

				if ( postType == 'product' ) {
					url += '&product_cat=' + productCat;
				} else {
					url += '&cat=' + cat;
				}

				url += '&limit=' + woosearchConfigs.search_limit;
				url += '&search_by=' + woosearchConfigs.search_by;
				
				$('.bew-woo-search-container').each(function(i){
					
					var append = $('.search-results-wrapper.bew-search-' + i);
					var $input2 = $(this).find( 'input.search-input' );
					
					$input2.devbridgeAutocomplete( {
						serviceUrl      : url,
						minChars        : woosearchConfigs.search_min_chars,
						appendTo        : append,
						deferRequestBy  : 300,
						beforeRender    : function( container ) {
							container.perfectScrollbar();
						},
						onSelect        : function( suggestion ) {
							if ( suggestion.url.length ) {
								window.location.href = suggestion.url;
							}

							if ( suggestion.id == - 2 ) {
								return;
							}
						},
						onSearchStart   : function() {
							$formWrapper.addClass( 'search-loading' );
						},
						onSearchComplete: function( query, suggestions ) {

							$formWrapper.removeClass( 'search-loading' );						

							if ( found && suggestions[0].id != - 1 ) {
								$ajaxNotice.fadeOut( 200 ).removeClass('has-notice');
							} else {
								$ajaxNotice.fadeIn( 200 ).addClass('has-notice');
							}

							if ( suggestions.length > 1 && suggestions[suggestions.length - 1].id == - 2 ) {

								// append View All link (always is the last element of suggestions array)
								var viewAll = suggestions[suggestions.length - 1];

								$formWrapper.find( '.autocomplete-suggestions' )
											.append( '<a class="search-view-all" href="' + viewAll.url + '"' + 'target="' + viewAll.target + '">' + viewAll.value + '</a>' );
							}

							$( '.autocomplete-suggestion' ).each( function() {
								if ( ! $( this ).html() ) {
									$( this ).remove();
								}
							} );
						},
						formatResult    : function( suggestion, currentValue ) {
							return generateHTML( suggestion, currentValue );				
							
						},
					} );
				
				});
			};

			var generateHTML = function( suggestion, currentValue ) {

				var postType    = $form.find( 'input[name="post_type"]' ).val(),
					pattern     = '(' + escapeRegExChars( currentValue ) + ')',
					returnValue = '';

				// not found
				if ( suggestion.id == - 1 ) {

					$ajaxNotice.text( suggestion.value ).fadeIn( 200 ).addClass('has-notice');

					return returnValue;
				}

				if ( suggestion.id == - 2 ) {
					return returnValue;
				}

				found = true;
				
				if ( suggestion.thumbnail ) {
					returnValue += ' <div class="suggestion-thumb">' + suggestion.thumbnail + '</div>';
				}

				if ( suggestion.id != - 2 ) {
					returnValue += '<div class="suggestion-details">';
				}

				var title = suggestion.value.replace( new RegExp( pattern, 'gi' ), '<ins>$1<\/ins>' )
									  .replace( /&/g, '&amp;' )
									  .replace( /</g, '&lt;' )
									  .replace( />/g, '&gt;' )
									  .replace( /"/g, '&quot;' )
									  .replace( /&lt;(\/?ins)&gt;/g, '<$1>' ) + '</a>';

				if ( suggestion.url.length ) {
					returnValue += '<a href="' + suggestion.url + '" class="suggestion-title">' + title + '</a>';
				} else {
					returnValue += '<h5 class="suggestion-title">' + title + '</h5>';
				}

				if ( postType === 'product' ) {

					var sku = suggestion.sku;

					if ( woosearchConfigs.search_by == 'sku' || woosearchConfigs.search_by == 'both' ) {

						sku = suggestion.sku.replace( new RegExp( pattern, 'gi' ), '<ins>$1<\/ins>' )
										.replace( /&/g, '&amp;' )
										.replace( /</g, '&lt;' )
										.replace( />/g, '&gt;' )
										.replace( /"/g, '&quot;' )
										.replace( /&lt;(\/?ins)&gt;/g, '<$1>' ) + '</a>';
					}

					if ( suggestion.sku ) {
						returnValue += '<span class="suggestion-sku">SKU: ' + sku + '</span>';
					}

					if ( suggestion.price ) {
						returnValue += '<span class="suggestion-price">' + suggestion.price + '</span>';
					}
				}

				if ( postType === 'post' ) {
					if ( suggestion.date ) {
						returnValue += '<span class="suggestion-date">' + suggestion.date + '</span>';
					}
				}

				if ( suggestion.excerpt && woosearchConfigs.search_excerpt_on ) {
					returnValue += '<p class="suggestion-excerpt">' + suggestion.excerpt + '</p>';
				}

				if ( suggestion.id != - 2 ) {
					returnValue += '</div>';
				}

				return returnValue;
			};

			var escapeRegExChars = function( value ) {
				return value.replace( /[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, '\\$&' );
			};
			
			var resultFullwidth = function() {
				
				$('.elementor-widget-bew-woo-search').each(function(i){
					var position =	$('#bew-search-' + i + ' .search-results-wrapper', this).offset(),
						marginleft = -position.left,				
						windowWidth   = $( window ).width();
						
					if ( windowWidth <= 767 ) {	
						$('#bew-search-' + i, this).removeClass( 'fullwidth'); 
					} else{
						if($('#bew-search-' + i, this ).hasClass( 'fullwidth' ) && $(this).hasClass( 'search-style--input' )){
						 console.log('hola2');
						 $('#bew-search-' + i + ' .search-results-wrapper').css('margin-left', marginleft);
						}	
					}
				});
			};

			categoriesSelectBox();
			events();
			ajaxSearch();
			resultFullwidth();

			var openSearch = function() {

				$body.addClass( 'search-opened' );
				$formWrapper.addClass( 'search--open' );
				$closeBtn.removeClass( 'btn--hidden' );

				setTimeout( function() {
					$input.focus();
				}, 500 );
			};

			var closeSearch = function() {

				$body.removeClass( 'search-opened' );
				$formWrapper.removeClass( 'search--open' );
				$closeBtn.addClass( 'btn--hidden' );

				setTimeout( function() {
					$input.blur();
				}, 500 );
			};
		}
	}
)( jQuery );

