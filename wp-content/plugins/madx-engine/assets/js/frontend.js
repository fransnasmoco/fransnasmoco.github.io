( function( $ ) {

	"use strict";

	var madxEngine = {

		currentMonth: null,
		currentRequest: {},
		activeCalendarDay: null,

		init: function() {

			var widgets = {
				'madx-listing-dynamic-field.default' : madxEngine.widgetDynamicField
			};

			$.each( widgets, function( widget, callback ) {
				window.madxartworkFrontend.hooks.addAction( 'frontend/element_ready/' + widget, callback );
			});

			$( document )
				.on( 'click.madxEngine', '.madx-calendar-nav__link', madxEngine.switchCalendarMonth )
				.on( 'click.madxEngine', '.madx-calendar-week__day-mobile-overlay', madxEngine.showCalendarEvent );

		},

		showCalendarEvent: function( event ) {

			var $this       = $( this ),
				$day        = $this.closest( '.madx-calendar-week__day' ),
				$week       = $day.closest( '.madx-calendar-week' ),
				$events     = $day.find( '.madx-calendar-week__day-content' ),
				activeClass = 'calendar-event-active';

			if ( $day.hasClass( activeClass ) ) {
				$day.removeClass( activeClass );
				madxEngine.activeCalendarDay.remove();
				madxEngine.activeCalendarDay = null;
				return;
			}

			if ( madxEngine.activeCalendarDay ) {
				madxEngine.activeCalendarDay.remove();
				$( '.' + activeClass ).removeClass( activeClass );
				madxEngine.activeCalendarDay = null;
			}

			$day.addClass( 'calendar-event-active' );

			madxEngine.activeCalendarDay = $( '<tr class="madx-calendar-week"><td colspan="7" class="madx-calendar-week__day madx-calendar-week__day-mobile"><div class="madx-calendar-week__day-mobile-event">' + $events.html() + '</div></td></tr>' );

			madxEngine.activeCalendarDay.insertAfter( $week );

		},

		widgetDynamicField: function( $scope ) {

			var $slider = $scope.find( '.madx-engine-gallery-slider' );

			if ( $slider.length ) {
				$slider.slick( $slider.data( 'atts' ) );
			}

		},

		switchCalendarMonth: function( $event ) {

			var $this     = $( this ),
				$calendar = $this.closest( '.madx-calendar' ),
				$widget   = $calendar.closest( '.madxartwork-widget-container' ),
				settings  = $calendar.data( 'settings' ),
				post      = $calendar.data( 'post' ),
				month     = $this.data( 'month' );

			$calendar.addClass( 'madx-calendar-loading' );

			madxEngine.currentRequest = {
				action: 'madx_engine_calendar_get_month',
				month: month,
				settings: settings,
				post: post,
			};

			$( document ).trigger( 'madx-engine-request-calendar' );

			$.ajax({
				url: madxEngineSettings.ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: madxEngine.currentRequest,
			}).done( function( response ) {
				if ( response.success ) {
					$widget.html( response.data.content );
				}
				$calendar.removeClass( 'madx-calendar-loading' );
			} );


		}

	};

	$( window ).on( 'madxartwork/frontend/init', madxEngine.init );

	window.madxEngine = madxEngine;

}( jQuery ) );
