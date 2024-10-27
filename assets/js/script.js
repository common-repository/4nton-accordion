/* Version: 1.0.7 */
jQuery(document).ready(function($){
	var to=(function(){var timers={};return function(callback,ms,x_id){if(!x_id){x_id='';}if(timers[x_id]){clearTimeout(timers[x_id]);}timers[x_id]=setTimeout(callback,ms);};})(),id,xstyle,xtop,slr=300,show_popup=false,allottedtime,expiration,ifautofit = 0,rd_bxslider,ads_scrolltop,apmu,apmuii,apmu_popup_title,apmu_multiple,apmu_loading = false,apmu_upload_type,apmu_media_type,apmu_submit_text,apmu_key;
	String.prototype.number_format = (function(d){
		var n = this,c = isNaN(d = Math.abs(d)) ? 2 : d,s = n < 0 ? "-" : "",i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
		return s + (j ? i.substr(0, j) + ',' : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + ',') + (c ? '.' + Math.abs(n - i).toFixed(c).slice(2) : "");
	});
	$( '#anton-accordion .anton-accordion-h4' ).click(function(e) {
		var _this = $( this );
		to(function(){
			var minus = parseInt( _this.attr( 'data-minus' ) );
			var ifscrollto = parseInt( _this.attr( 'data-scrollto' ) );
			if( _this.hasClass( 'anton-accordion-active' ) ){
				if( parseInt( _this.attr( 'data-toggle' ) ) ){
					_this.next( '.anton-accordion-toggle-content' ).hide( 'slow' ).prev( '.anton-accordion-h4' ).removeClass( 'anton-accordion-active' );
				}
				return; 
			}
			$( '#anton-accordion .anton-accordion-toggle-content' ).each(function(index, element) {
				$( this ).hide( 'slow' ).prev( '.anton-accordion-h4' ).removeClass( 'anton-accordion-active' );
			});
			var activeclass = _this.next( '.anton-accordion-toggle-content' ).attr( 'class' );
			var activeclass = activeclass.replace( 'anton-accordion-toggle-content ', '' );
			$( '#anton-accordion-active' ).val( activeclass );
			_this.addClass( 'anton-accordion-active' ).next( '.anton-accordion-toggle-content' ).show( 'slow', function(){
				var scrollto = $( this ).offset().top - minus;
				if( ifscrollto ) { $( 'html,body' ).animate({ scrollTop: scrollto }, 500 ); }
			});
		},200);
	});
});