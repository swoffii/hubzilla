<?php

function pubsites_content(&$a) {
	require_once('include/dir_fns.php'); 
	$dirmode = intval(get_config('system','directory_mode'));

	if(($dirmode == DIRECTORY_MODE_PRIMARY) || ($dirmode == DIRECTORY_MODE_STANDALONE)) {
		$url = z_root() . '/dirsearch';
	}
	if(! $url) {
		$directory = find_upstream_directory($dirmode);
		$url = $directory['url'] . '/dirsearch';
	}
	$url .= '/sites';

	$o .= '<div class="generic-content-wrapper-styled">';

	$o .= '<h2>' . t('Public Hubs') . '</h2>';

	$o .= '<div class="descriptive-text">' . 
		t('The listed hubs allow public registration for the $Projectname network. All hubs in the network are interlinked so membership on any of them conveys membership in the network as a whole. Some sites may require subscription or provide tiered service plans. The hub <strong>may</strong> provide additional details.') . '</div>' . EOL;

	$ret = z_fetch_url($url);
	if($ret['success']) {
		$j = json_decode($ret['body'],true);
		if($j) {
			$o .= '<table class="table table-striped table-hover"><tr><td>' . t('Hub URL') . '</td><td>' . t('Access Type') . ' / ' . '<br />' . t('Registration Policy') . '</td><td>' . t('Project') . '</td><td colspan="2">' . t('Ratings') . '</td></tr>';
			if($j['sites']) {
				foreach($j['sites'] as $jj) {
					$host = strtolower(substr($jj['url'],strpos($jj['url'],'://')+3));
					$rate_links = ((local_channel()) ? '<td><a href="rate?f=&target=' . $host . '" class="btn-btn-default"><i class="icon-check"></i> ' . t('Rate') . '</a></td>' : '');
					$location = '';
					if(!empty($jj['location'])) { 
						$location = '<p title="' . t('Location') . '" style="margin: 5px 5px 0 0; text-align: right"><i class="icon-globe"></i> ' . $jj['location'] . '</p>'; 
						}
					else {
						$location = '<br />&nbsp;';
						}
					$urltext = str_replace(array('https://'), '', $jj['url']);
					$o .= '<tr><td><a href="'. (($jj['sellpage']) ? $jj['sellpage'] : $jj['url'] . '/register' ) . '" ><i class="icon-link"></i> ' . $urltext . '</a>' . $location . '</td><td>' . $jj['access'] . ' / ' . $jj['register'] . '</td><td>' . $jj['project'] . '</td><td><a href="ratings/' . $host . '" class="btn-btn-default"><i class="icon-eye-open"></i> ' . t('View') . '</a></td>' . $rate_links . '</tr>';
				}
			}
	
			$o .= '</table>';

			$o .= '</div>';

		}
	}
	return $o;
}
