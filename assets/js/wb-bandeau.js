/**
 * WebsourceBandeau : configuration de la rotation (délais d'animation) et
 * gestion de la fermeture par le visiteur, sans jQuery ni dépendance externe.
 */
( function () {
	'use strict';

	function ready( fn ) {
		if ( document.readyState !== 'loading' ) {
			fn();
		} else {
			document.addEventListener( 'DOMContentLoaded', fn );
		}
	}

	function getStorage() {
		try {
			window.localStorage.setItem( '__wb_test__', '1' );
			window.localStorage.removeItem( '__wb_test__' );
			return window.localStorage;
		} catch ( e ) {
			return null;
		}
	}

	function isDismissed( config ) {
		var storage = getStorage();
		if ( ! storage ) {
			return false;
		}
		var raw = storage.getItem( config.storageKey );
		if ( ! raw ) {
			return false;
		}
		var dismissedAt = parseInt( raw, 10 );
		if ( isNaN( dismissedAt ) ) {
			return false;
		}
		if ( config.rememberDays <= 0 ) {
			return true; // Mémorisé sans expiration (jusqu'à vidage du stockage local).
		}
		var elapsedMs = Date.now() - dismissedAt;
		var maxMs = config.rememberDays * 24 * 60 * 60 * 1000;
		return elapsedMs < maxMs;
	}

	function markDismissed( config ) {
		var storage = getStorage();
		if ( storage ) {
			storage.setItem( config.storageKey, String( Date.now() ) );
		}
	}

	ready( function () {
		var bar = document.getElementById( 'wb-bandeau' );
		if ( ! bar ) {
			return;
		}

		var config = window.wbBandeauConfig || { dismissible: false, rememberDays: 1, storageKey: 'wb_bandeau_dismissed' };

		if ( isDismissed( config ) ) {
			bar.classList.add( 'wb-bandeau-hidden' );
			return;
		}

		// Configure la rotation : compte les messages et répartit les délais d'animation.
		var track = bar.querySelector( '.wb-bandeau-track' );
		var messages = bar.querySelectorAll( '.wb-bandeau-message' );
		var count = messages.length;

		if ( track ) {
			track.setAttribute( 'data-wb-count', String( count ) );

			var rotation = parseFloat(
				getComputedStyle( document.documentElement ).getPropertyValue( '--wb-rotation' )
			) || 6;

			var totalDuration = rotation * count;

			messages.forEach( function ( message, index ) {
				message.style.animationDuration = totalDuration + 's';
				message.style.animationDelay = ( index * rotation ) + 's';
			} );
		}

		var closeButton = bar.querySelector( '.wb-bandeau-close' );
		if ( closeButton && config.dismissible ) {
			closeButton.addEventListener( 'click', function () {
				bar.classList.add( 'wb-bandeau-hidden' );
				markDismissed( config );
			} );
		}
	} );
} )();
