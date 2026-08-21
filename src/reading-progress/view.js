/* global requestAnimationFrame, ResizeObserver */
import { store } from '@wordpress/interactivity';

const NAMESPACE = 'next-reading-progress';

// Module-scoped: progress is page-wide, so a single shared listener pair
// drives every block instance on the page in lockstep.
let listenersBound = false;
let ticking = false;

function computePercentage() {
	const doc = document.documentElement;
	const scrollTop = window.scrollY || doc.scrollTop || 0;
	const scrollable = doc.scrollHeight - doc.clientHeight;

	if ( scrollable <= 0 ) {
		return 0;
	}

	return Math.min( 100, Math.max( 0, ( scrollTop / scrollable ) * 100 ) );
}

const { state } = store( NAMESPACE, {
	state: {
		percentage: 0,
		get percentageCss() {
			return `${ state.percentage }%`;
		},
		get percentageRounded() {
			return Math.round( state.percentage );
		},
	},
	callbacks: {
		init() {
			if ( listenersBound ) {
				return;
			}
			listenersBound = true;

			const update = () => {
				if ( ticking ) {
					return;
				}
				ticking = true;
				requestAnimationFrame( () => {
					state.percentage = computePercentage();
					ticking = false;
				} );
			};

			update();
			window.addEventListener( 'scroll', update, { passive: true } );
			window.addEventListener( 'resize', update, { passive: true } );

			if ( 'ResizeObserver' in window ) {
				new ResizeObserver( update ).observe( document.body );
			}
		},
	},
} );
