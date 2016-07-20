<?php

class td_block_weather extends td_block {


	function render($atts, $content = null) {
		if (empty($atts['custom_title'])) {
			$atts['custom_title'] = 'Weather';
		}

		parent::render($atts); // sets the live atts, $this->atts, $this->block_uid, $this->td_query (it runs the query)

		if (empty($td_column_number)) {
			$td_column_number = td_util::vc_get_column_number(); // get the column width of the block from the page builder API
		}

		$buffy = ''; //output buffer
		$buffy .= '<div class="' . $this->get_block_classes() . '">';
			//get the block title
			$buffy .= $this->get_block_title();
			$buffy .= '<div id=' . $this->block_uid . ' class="td-weather-wrap td_block_inner td-column-' . $td_column_number . '">';
				$buffy.= td_weather::render_generic($atts, $this->block_uid);
			$buffy .= '</div>';
		$buffy .= '</div> <!-- ./block -->';
		return $buffy;
	}


}