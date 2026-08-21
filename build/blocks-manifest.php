<?php
// This file is generated. Do not modify it manually.
return array(
	'reading-progress' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'next/reading-progress',
		'version' => '0.1.0',
		'title' => 'Reading Progress Bar',
		'category' => 'design',
		'icon' => 'chart-bar',
		'description' => 'ページ全体のスクロール進捗を表示するリーディングプログレスバーブロック。',
		'keywords' => array(
			'progress',
			'scroll',
			'reading bar'
		),
		'example' => array(
			
		),
		'attributes' => array(
			'useCustomWidth' => array(
				'type' => 'boolean',
				'default' => false
			),
			'customWidth' => array(
				'type' => 'number',
				'default' => 100
			),
			'customWidthUnit' => array(
				'type' => 'string',
				'default' => '%'
			),
			'barHeight' => array(
				'type' => 'number',
				'default' => 6
			),
			'barHeightUnit' => array(
				'type' => 'string',
				'default' => 'px'
			),
			'fillColor' => array(
				'type' => 'string'
			),
			'fillGradient' => array(
				'type' => 'string'
			),
			'trackColor' => array(
				'type' => 'string',
				'default' => '#e0e0e0'
			),
			'zIndex' => array(
				'type' => 'number'
			)
		),
		'supports' => array(
			'align' => array(
				'wide',
				'full'
			),
			'html' => false,
			'interactivity' => true,
			'spacing' => array(
				'margin' => true
			)
		),
		'textdomain' => 'next-reading-progress',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php',
		'viewScriptModule' => 'file:./view.js'
	)
);
