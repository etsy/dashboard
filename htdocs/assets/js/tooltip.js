/*-----------------------------------------------------------------------------------------------*/
/*                                      SIMPLE jQUERY TOOLTIP                                    */
/*                                      VERSION: 1.1                                             */
/*                                      AUTHOR: jon cazier                                       */
/*                                      EMAIL: jon@3nhanced.com                                  */
/*                                      WEBSITE: 3nhanced.com                                    */
/*-----------------------------------------------------------------------------------------------*/

$(document).ready(function() {
	$('.toolTip').hover(
		function() {
		this.tip = this.title;
		$(this).append(
			'<div class="toolTipWrapper">'
				+'<div class="toolTipTop"></div>'
				+'<div class="toolTipMid">'
					+this.tip
				+'</div>'
				+'<div class="toolTipBtm"></div>'
			+'</div>'
		);
		this.title = "";
		this.width = $(this).width();
//		$(this).find('.toolTipWrapper').css({left:this.width-22})
		$('.toolTipWrapper').fadeIn(100);
	},
	function() {
		$('.toolTipWrapper').fadeOut(200);
		$(this).children().remove();
			this.title = this.tip;
		}
	);
});
