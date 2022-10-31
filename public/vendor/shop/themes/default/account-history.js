/**
 * Account history actions
 */
AimeosAccountHistory = {

	/**
	 * Shows history details without page reload
	 */
	onToggleDetail() {

		$(".account-history").on("click", ".history-item .action .btn", ev => {

			const target = $(ev.currentTarget).closest(".history-item");
			const details = $(".account-history-detail", target);

			$(".btn.show", target).toggleClass('hidden');
			$(".btn.close", target).toggleClass('hidden');

			slideToggle(details[0], 300);

			return false;
		});
		$('.address-billing .act-show').click(function(){
			if($('#address-payment').hasClass('collapse')){
				$('#address-payment').removeClass('collapse');
			}else{
				$('#address-payment').addClass('collapse');
			}
		})
		$('.address-delivery-new .act-show').click(function(){
			if($('#address-delivery-1').hasClass('collapse')){
				$('#address-delivery-1').removeClass('collapse');
			}else{
				$('#address-delivery-1').addClass('collapse');
			}
		})
	},


	/**
	 * Initializes the account history actions
	 */
	init() {
		this.onToggleDetail();
	}
};


$(() => {
	AimeosAccountHistory.init();
});