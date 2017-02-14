<?php

namespace App\Http\Composers;

use DOMDocument;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * A class to help insert SVG images without the need to inline
 * a huge block of code. We just need to call the specific
 * image from a blade template like so:
 * {!! $svgImages['logo'] !!}
 *
 */
class SVGImagesComposer
{
	public function compose(View $view)
	{
		$svgImages = [
			'logo' => $this->logo()
		];

		$view->with('svgImages', $svgImages);
	}

	public function logo()
	{
		$image_location = public_path('images/logos/main-logo.svg');
		$svg = new DOMDocument();
		$svg->load($image_location);
		$svg->documentElement->setAttribute("class", "logo");
		return $svg->saveXML($svg->documentElement);
	}
}
