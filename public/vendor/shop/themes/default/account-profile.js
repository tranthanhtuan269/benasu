/**
 * Account profile actions
 */
AimeosAccountProfile = {

	/**
	 * Reset and close the new address form
	 */
	onAddress() {

		document.querySelectorAll(".account-profile-address .panel").forEach(el => {
			el.addEventListener("show.bs.collapse", ev => {
				$(".act-show", ev.currentTarget).removeClass("act-show").addClass("act-hide");
			});
		});

		document.querySelectorAll(".account-profile-address .panel").forEach(el => {
			el.addEventListener("hidden.bs.collapse", ev => {
				$(".act-hide", ev.currentTarget).removeClass("act-hide").addClass("act-show");
			});
		});
	},


	/**
	 * Adds a new delivery address form
	 */
	onAddressNew() {

		document.querySelectorAll(".account-profile-address .address-delivery-new").forEach(el => {
			el.addEventListener("show.bs.collapse", ev => {
				$("input,select", ev.currentTarget).prop("disabled", false);
			});
		});

		document.querySelectorAll(".account-profile-address .address-delivery-new").forEach(el => {
			el.addEventListener("hidden.bs.collapse", ev => {
				$("input,select", ev.currentTarget).prop("disabled", true);
			});
		});

		document.querySelectorAll(".account-profile-address .address-delivery-new .btn-cancel").forEach(el => {
			el.addEventListener("click", ev => {
				var node = $(".panel-body", $(ev.currentTarget).parents(".address-delivery-new")).get(0);
				bootstrap.Collapse.getInstance(node).hide();
			});
		});
	},


	/**
	 * Checks address form for missing or wrong values
	 */
	onCheckMandatory() {

		$(".account-profile .form-item").on("blur", "input,select",() => {
			const value = $(this).val();
			const node = $(this).parents(".form-item");
			const regex = new RegExp(node.data('regex') || '.*');

			if((value !== '' && value.match(regex)) || (value === '' && !node.hasClass("mandatory"))) {
				node.removeClass("error").addClass("success");
			} else {
				node.removeClass("success").addClass("error");
			}
		});

		$(".account-profile form").on("submit", () => {
			let retval = true;
			const nodes = [];

			$(".form-list .mandatory", this).each((idx, el) => {

				const elem = $(el);
				const value = $("input,select", elem).val();

				if(value === null || value.trim() === "") {
					elem.addClass("error");
					nodes.push(el);
					retval = false;
				} else {
					elem.removeClass("error");
				}
			});

			return retval;
		});
	},


	/**
	 * Initializes the account watch actions
	 */
	init() {
		this.onAddress();
		this.onAddressNew();
		this.onCheckMandatory();

		$('.btn-save-refer').click(function(){
			var prefer_code = $('#user-presenter').val();
			if(prefer_code.length <= 0){
				return false;
			}else{
				var data = {
					'prefer_code' : prefer_code
				}
				$.ajax({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					method: 'POST',
					data : data,
					dataType: 'json',
					url: '/users/refer', // returned from OPTIONS call
				}).done( function( result ) {
					// var data = result.data;
					console.log(data);
				});
			}
		})

		$('#create-coupon-btn').click(function(){
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				method: 'POST',
				dataType: 'json',
				url: '/users/create-coupon', // returned from OPTIONS call
			}).done( function( result ) {
				console.log(result);
				if(result.status == -1){
					Swal.fire(
						'Error!',
						result.message,
						'error'
					)
				}else{
					Swal.fire(
						'Congratulations!',
						result.message,
						'success'
					)
					var count = $('#coupon-list tr').length;

					var html = '<tr>\
							      <th scope="row">'+(count+1)+'</th>\
							      <td>'+result.coupon+'</td>\
								  <td>valid</td>\
							    </tr>';
					$('#coupon-list').append(html);
				}
			});
		})
	}
};


$(function() {
	AimeosAccountProfile.init();
});