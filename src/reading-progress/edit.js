import { __, sprintf } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
	useSetting,
} from '@wordpress/block-editor';
import {
	PanelBody,
	ToggleControl,
	TabPanel,
	ColorPalette,
	GradientPicker,
	// NumberControl/UnitControl are still only exported under their
	// __experimental* names in the WordPress versions this plugin supports
	// (verified against the installed @wordpress/components build) — there
	// is no stable alternative to opt into yet.
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalNumberControl as NumberControl,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalUnitControl as UnitControl,
} from '@wordpress/components';

const WIDTH_UNITS = [ 'px', '%', 'em', 'rem', 'vw' ];
const HEIGHT_UNITS = [ 'px', 'em', 'rem' ];

// Illustrative-only value: the editor canvas has no real scroll to measure.
const PREVIEW_PERCENT = 42;

function buildTrackStyle( attributes ) {
	const {
		useCustomWidth,
		customWidth,
		customWidthUnit,
		barHeight,
		barHeightUnit,
		trackColor,
		zIndex,
	} = attributes;

	const style = {
		height: `${ barHeight }${ barHeightUnit }`,
		backgroundColor: trackColor,
		position: 'relative',
		overflow: 'hidden',
	};

	if ( useCustomWidth ) {
		style.width = `${ customWidth }${ customWidthUnit }`;
	}

	if ( zIndex !== undefined && zIndex !== '' ) {
		style.zIndex = zIndex;
	}

	return style;
}

function buildFillStyle( attributes, percentage ) {
	const { fillColor, fillGradient } = attributes;

	const style = {
		height: '100%',
		width: `${ percentage }%`,
	};

	if ( fillGradient ) {
		style.backgroundImage = fillGradient;
	} else if ( fillColor ) {
		style.backgroundColor = fillColor;
	}

	return style;
}

export default function Edit( { attributes, setAttributes } ) {
	const {
		useCustomWidth,
		customWidth,
		customWidthUnit,
		barHeight,
		barHeightUnit,
		fillColor,
		fillGradient,
		trackColor,
		zIndex,
	} = attributes;

	const blockProps = useBlockProps();

	const colors = useSetting( 'color.palette' ) || [];
	const gradients = useSetting( 'color.gradients' ) || [];

	const trackStyle = buildTrackStyle( attributes );
	const fillStyle = buildFillStyle( attributes, PREVIEW_PERCENT );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( '幅', 'next-reading-progress' ) }>
					<ToggleControl
						label={ __(
							'幅を数値で指定する',
							'next-reading-progress'
						) }
						help={ __(
							'ブロックの配置（コンテンツ幅/幅広/全幅）と併用できます。',
							'next-reading-progress'
						) }
						checked={ !! useCustomWidth }
						onChange={ ( value ) =>
							setAttributes( { useCustomWidth: value } )
						}
					/>
					{ useCustomWidth && (
						<UnitControl
							label={ __( '幅', 'next-reading-progress' ) }
							value={ `${ customWidth }${ customWidthUnit }` }
							units={ WIDTH_UNITS.map( ( unit ) => ( {
								value: unit,
								label: unit,
							} ) ) }
							onChange={ ( newValue ) => {
								const match =
									String( newValue ).match(
										/^([\d.]+)(.*)$/
									);
								if ( ! match ) {
									return;
								}
								setAttributes( {
									customWidth: Number( match[ 1 ] ),
									customWidthUnit:
										match[ 2 ] || customWidthUnit,
								} );
							} }
						/>
					) }
				</PanelBody>

				<PanelBody
					title={ __( 'バーの太さ', 'next-reading-progress' ) }
				>
					<UnitControl
						label={ __( '高さ', 'next-reading-progress' ) }
						value={ `${ barHeight }${ barHeightUnit }` }
						units={ HEIGHT_UNITS.map( ( unit ) => ( {
							value: unit,
							label: unit,
						} ) ) }
						onChange={ ( newValue ) => {
							const match =
								String( newValue ).match( /^([\d.]+)(.*)$/ );
							if ( ! match ) {
								return;
							}
							setAttributes( {
								barHeight: Number( match[ 1 ] ),
								barHeightUnit: match[ 2 ] || barHeightUnit,
							} );
						} }
					/>
				</PanelBody>

				<PanelBody title={ __( 'カラー', 'next-reading-progress' ) }>
					<p className="next-reading-progress-color-label">
						{ __( '進捗バーの色', 'next-reading-progress' ) }
					</p>
					<TabPanel
						tabs={ [
							{
								name: 'solid',
								title: __( '単色', 'next-reading-progress' ),
							},
							{
								name: 'gradient',
								title: __(
									'グラデーション',
									'next-reading-progress'
								),
							},
						] }
						initialTabName={ fillGradient ? 'gradient' : 'solid' }
					>
						{ ( tab ) =>
							tab.name === 'gradient' ? (
								<GradientPicker
									value={ fillGradient }
									gradients={ gradients }
									onChange={ ( value ) =>
										setAttributes( {
											fillGradient: value,
											fillColor: undefined,
										} )
									}
								/>
							) : (
								<ColorPalette
									colors={ colors }
									value={ fillColor }
									onChange={ ( value ) =>
										setAttributes( {
											fillColor: value,
											fillGradient: undefined,
										} )
									}
								/>
							)
						}
					</TabPanel>

					<p className="next-reading-progress-color-label">
						{ __(
							'未読部分（トラック）の色',
							'next-reading-progress'
						) }
					</p>
					<ColorPalette
						colors={ colors }
						value={ trackColor }
						onChange={ ( value ) =>
							setAttributes( {
								trackColor: value || '#e0e0e0',
							} )
						}
					/>
				</PanelBody>

				<PanelBody
					title={ __( '詳細設定', 'next-reading-progress' ) }
					initialOpen={ false }
				>
					<NumberControl
						label={ __( 'z-index', 'next-reading-progress' ) }
						help={ __(
							'他の要素と重なる場合のみ指定してください。通常は空欄のままで問題ありません。',
							'next-reading-progress'
						) }
						value={ zIndex ?? '' }
						onChange={ ( value ) =>
							setAttributes( {
								zIndex:
									value === '' ? undefined : Number( value ),
							} )
						}
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<div className="next-reading-progress" style={ trackStyle }>
					<div
						className="next-reading-progress__fill"
						style={ fillStyle }
					/>
				</div>
				<span className="next-reading-progress__editor-note">
					{ sprintf(
						/* translators: %d: illustrative progress percentage shown only in the editor */
						__(
							'プレビュー: %d%%（編集画面のみのイメージ表示です）',
							'next-reading-progress'
						),
						PREVIEW_PERCENT
					) }
				</span>
			</div>
		</>
	);
}
