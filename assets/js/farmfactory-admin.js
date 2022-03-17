/**
 * Admin Scripts
 */
(function( $ ){
	"use strict";

	var loaderOverlay  = document.getElementById('farmfactory_loaderOverlay');
	var rewardsAddress = document.getElementById('rewardsAddress');
	var stakingAddress = document.getElementById('stakingAddress');
	var duration       = document.getElementById('farmfactory_duration');
	var withdrawLockPeriod       = document.getElementById('farmfactory_withdrawLockPeriod');
	var decimal        = document.getElementById('farmfactory_reward_decimals');
	var button         = document.getElementById('farmfactory_deploy_button');

	var farmAddress        = document.getElementById('farmfactory_farmAddress');
	var amount             = document.getElementById('amount');
	var startFarmingButton = document.getElementById('farmfactory_startFarmingButton');

	farmDeployer.init({
		onStartLoading: () => {
			// show loader
			button.disabled = true;
		},
		onFinishLoading: () => {
			// hide loader
			button.disabled = false;
		},
		onError: (err) => {
			console.error(err);
			button.disabled = true;
			alert(err);
		}
	});

	$( button ).on( 'click', function(e) {
		e.preventDefault();

    if (!rewardsAddress || !stakingAddress || !duration || !decimal || !withdrawLockPeriod) {
      alert('All fields should be filled: rewardsAddress, stakingAddress, duration, withdrawLockPeriod, decimal.');
      return;
    }

    if (button.disabled) {
      return
    }

    button.disabled = true;
    loaderOverlay.classList.add('visible');

		farmDeployer.deploy({
			rewardsAddress: rewardsAddress.value,
			stakingAddress: stakingAddress.value,
			duration: duration.value,
			withdrawLockPeriod: withdrawLockPeriod.value,
			decimal: decimal.value,
      onTrx: (trxHash) => {
			  alert(`Transaction hash: ${trxHash}. Send this hash to the support if you have a problem with deploy.`)
      },
			onSuccess: (address) => {
				console.log('Contract address:', address);
				button.disabled = false;
				loaderOverlay.classList.remove('visible');
				document.getElementById('farmfactory_farmAddress').value = address;
			},
			onError: (err) => {
				console.error(err);
				button.disabled = true;
				loaderOverlay.classList.remove('visible');
				alert(err);
			}
		});

	});

	startFarmingButton.addEventListener('click', () => {
		if (farmDeployer.disabled) {
			return
		}

		farmDeployer.disabled = true;
		loaderOverlay.classList.add('visible');

		farmDeployer.startFarming({
			rewardsAddress: document.getElementById('rewardsAddress').value,
			farmAddress: farmAddress.value,
			amount: amount.value,
			onSuccess: () => {
				console.log('Farming started');
				startFarmingButton.disabled = false;
				loaderOverlay.classList.remove('visible');
			},
			onError: (err) => {
				console.error(err);
				startFarmingButton.disabled = false;
				loaderOverlay.classList.remove('visible');
				alert(err);
			}
		});
	});












	/**
	 * @namespace wp.media.featuredImage
	 * @memberOf wp.media
	 */
	wp.media.featuredIcon = {
		/**
		 * Get the featured image post ID
		 *
		 * @return {wp.media.view.settings.post.featuredImageId|number}
		 */
		get: function() {
			return wp.media.view.settings.post.featuredIconId;
		},
		/**
		 * Sets the featured image ID property and sets the HTML in the post meta box to the new featured image.
		 *
		 * @param {number} id The post ID of the featured image, or -1 to unset it.
		 */
		set: function( id ) {
			var settings = wp.media.view.settings;

			settings.post.featuredIconId = id;

			wp.media.post( 'get-post-thumbnail-html', {
				post_id:      settings.post.id,
				thumbnail_id: settings.post.featuredIconId,
				_wpnonce:     settings.post.nonce
			}).done( function( html ) {
				if ( '0' === html ) {
					window.alert( wp.i18n.__( 'Could not set that as the thumbnail image. Try a different attachment.' ) );
					return;
				}
				$( '.inside', '#postimagediv' ).html( html );
			});
		},
		/**
		 * Remove the featured image id, save the post thumbnail data and
		 * set the HTML in the post meta box to no featured image.
		 */
		remove: function() {
			wp.media.featuredIcon.set( -1 );
		},
		/**
		 * The Featured Image workflow
		 *
		 * @this wp.media.featuredImage
		 *
		 * @return {wp.media.view.MediaFrame.Select} A media workflow.
		 */
		frame: function() {
			if ( this._frame ) {
				wp.media.frame = this._frame;
				return this._frame;
			}

			this._frame = wp.media({
				state: 'featured-image',
				states: [ new wp.media.controller.FeaturedImage() , new wp.media.controller.EditImage() ]
			});

			this._frame.on( 'toolbar:create:featured-image', function( toolbar ) {
				/**
				 * @this wp.media.view.MediaFrame.Select
				 */
				this.createSelectToolbar( toolbar, {
					text: wp.media.view.l10n.setFeaturedImage
				});
			}, this._frame );

			this._frame.on( 'content:render:edit-image', function() {
				var selection = this.state('featured-image').get('selection'),
					view = new wp.media.view.EditImage( { model: selection.single(), controller: this } ).render();

				this.content.set( view );

				// After bringing in the frame, load the actual editor via an Ajax call.
				view.loadEditor();

			}, this._frame );

			this._frame.state('featured-image').on( 'select', this.select );
			return this._frame;
		},
		/**
		 * 'select' callback for Featured Image workflow, triggered when
		 *  the 'Set Featured Image' button is clicked in the media modal.
		 *
		 * @this wp.media.controller.FeaturedImage
		 */
		select: function() {
			var selection = this.get('selection').single();

			if ( ! wp.media.view.settings.post.featuredImageId ) {
				return;
			}

			wp.media.featuredImage.set( selection ? selection.id : -1 );
		},
		/**
		 * Open the content media manager to the 'featured image' tab when
		 * the post thumbnail is clicked.
		 *
		 * Update the featured image id when the 'remove' link is clicked.
		 */
		init: function() {
			$('#farmimagediv').on( 'click', '#set-farm-thumbnail', function( event ) {
				event.preventDefault();
				// Stop propagation to prevent thickbox from activating.
				event.stopPropagation();

				wp.media.featuredIcon.frame().open();
			}).on( 'click', '#remove-farm-thumbnail', function() {
				wp.media.featuredIcon.remove();
				return false;
			});
		}
	};

	//$( wp.media.featuredIcon.init );

	/**
	 * Select/Upload icon
	 */
	$('#farmimagediv').on('click', '#set-farm-thumbnail', function(e){
		e.preventDefault();

		var button = $(this),
			custom_uploader = wp.media({
				title: farmfactory.l18n.featuredImage,
				library : {
					type : 'image'
				},
			button: {
				text: farmfactory.l18n.setFeaturedImage,
			},
			multiple: false
		}).on('select', function() {
			var attachment = custom_uploader.state().get('selection').first().toJSON();

			$('#_farm_thumbnail_id').val( attachment.id );

			var html = '<p><a href="#" id="set-farm-thumbnail"><img src="' + attachment.url + '"></a></p>' +
			'<p class="howto">' + farmfactory.l18n.clickTheImage + '</p>' +
			'<p><a href="#" id="remove-farm-thumbnail">' + farmfactory.l18n.removeFeaturedImage + '</a></p>';
			$('.farmfactory-thumbnail-container').html( html );
		})
		.open();
	});

	/**
	 * Select/Upload icon
	 */
	$('#farmimagediv').on('click', '#remove-farm-thumbnail', function(e){
		e.preventDefault();

		$('#_farm_thumbnail_id').val( '-1' );

		var html = '<p><a href="#" id="set-farm-thumbnail">' + farmfactory.l18n.setFeaturedImage + '</a></p>';

		$('.farmfactory-thumbnail-container').html( html );
	});

	/**
	 * Clipboard
	 */
	var copyFarmShortcodeClipboard = new ClipboardJS( '.copy-farm-shortcode' );
	var copyFarmShortcodeSuccessTimeout;
	copyFarmShortcodeClipboard.on( 'success', function( event ) {
		var triggerElement = $( event.trigger );
		var successElement = $( '.success', triggerElement.closest( '.copy-to-clipboard-container' ) );

		// Clear the selection and move focus back to the trigger.
		event.clearSelection();
		// Handle ClipboardJS focus bug, see https://github.com/zenorocha/clipboard.js/issues/680
		triggerElement.trigger( 'focus' );

		// Show success visual feedback.
		clearTimeout( copyFarmShortcodeSuccessTimeout );
		successElement.removeClass( 'hidden' );

		// Hide success visual feedback after 3 seconds since last success.
		copyFarmShortcodeSuccessTimeout = setTimeout( function() {
			successElement.addClass( 'hidden' );
		}, 3000 );

	} );

})( jQuery );
