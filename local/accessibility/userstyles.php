<?php


require_once('../../config.php');

// Including config.php overwrites header content-type in moodle 2.8.
header('Content-Type: text/css', true);
header("X-Content-Type-Options: nosniff"); // For IE.
header('Cache-Control: no-cache');


$defaults = array(
    // The fg1 and bg1 would be reset/default colour - do not define it.
    'bg2' => '#FFFFCC',
    'fg2' => '#000', // Default theme colours will be unchanged.
    'bg3' => '#99CCFF',
    'fg3' => '#000',
    'bg4' => '#000000',
    'fg4' => '#FFFF00',
);


$fontsize = optional_param('fontsize', 0,PARAM_INT);
$colourscheme = optional_param('colourscheme', 0,PARAM_INT);

if ($fontsize>0 && $fontsize!=100) {
    echo '
	
	body h1{font-size:' . (0.32 * $fontsize) . 'px !important}
	body h2{font-size:' . (0.28 * $fontsize) . 'px !important}
	body h3{font-size:' . (0.24 * $fontsize) . 'px !important}
	body h4{font-size:' . (0.20 * $fontsize) . 'px !important}
	body h5{font-size:' . (0.16 * $fontsize) . 'px !important}
	body h6{font-size:' . (0.12 * $fontsize) . 'px !important}
	
	/* SPOLED WCAG CORRECT */
	.jumbotron header h1{font-size:' . (0.60 * $fontsize) . 'px !important}
	.navbar-light .navbar-nav .nav-link{font-size:' . (0.17 * $fontsize) . 'pt !important}
	.block_coursetoc .card-title {font-size:' . (0.40 * $fontsize) . 'px !important}
	.coursetoc-title {font-size:' . (0.17 * $fontsize) . 'px !important}
	.course-2.path-mod-lesson #page-content h3, .course-1 .book_content h3, .front h2 {font-size:' . (0.40 * $fontsize) . 'px !important}
	.course-2 .menu.block h5, .course-1 .block_book_toc h5 {font-size:' . (0.40 * $fontsize) . 'px !important}
	#about-project {font-size:' . (0.60 * $fontsize) . 'px !important}
	#intro {font-size:' . (0.26 * $fontsize) . 'px !important}
	.icon{font-size:' . (0.16 * $fontsize) . 'px !important}

	body { /* block elements */
		font-size: ' . $fontsize . '% !important;
		line-height:1.5; /*WCAG 2.0*/
	}

	body * {
		line-height: inherit !important;
		font-size: inherit !important;
	}
	
	
	';
}
else {
		echo '';

}

if ($colourscheme>0) {
    // If $colourscheme == 1, reset, so don't output any styles.
    if ($colourscheme > 1 && $colourscheme < 5) { // This is how many declarations we defined in edit_form.php.
        $fgcolour = $defaults['fg' . $colourscheme];
        $bgcolour = $defaults['bg' . $colourscheme];
    }

    // Keep in mind that :not selector cannot work properly with IE <= 8 so this will not be included.
    $notselector = '';
    if (!preg_match('/(?i)msie [1-8]/', $_SERVER['HTTP_USER_AGENT'])) {
        $notselector = ':not([class*="mce"]):not([id*="mce"]):not([id*="editor"])';
    }

    // If no colours defined, no output, it will remain as default.
    if (!empty($bgcolour)) {
        echo '
		forumpost .topic {
			background-image: none !important;
		}
		*' . $notselector . '{
			/* it works well only with * selector but mce editor gets unusable */
			background-color: ' . $bgcolour . ' !important;
			background-image: none !important;
			text-shadow:none !important;
		}
		';
    }

    // It is recommended not to change forground colour.
    if (!empty($fgcolour)) {
        echo '
		*' . $notselector . '{
			/* it works well only with * selector but mce editor gets unusable */
			color: ' . $fgcolour . ' !important;
		}
		#content a, .tabrow0 span {
			color: ' . $fgcolour . ' !important;
		}
		.tabrow0 span:hover {
			text-decoration: underline;
		}
		.block_accessibility .outer {
			border-color: ' . $bgcolour . ' !important;
		}
		';
    }

}


for ($i = 2; $i < 5; $i++) {  // This is how many declarations we defined in defaults.php.
    $colourscheme = $i;

    $fgcolour = $defaults['fg' . $colourscheme];
    $bgcolour = $defaults['bg' . $colourscheme];

    echo '#local_accessibility_colour' . $colourscheme . '{';
    if (!empty($fgcolour)) {
        echo 'color:' . $fgcolour . ' !important;';
    }
    if (!empty($bgcolour)) {
        echo 'background-color:' . $bgcolour . ' !important;';
    }
    echo '}';
}

echo '



#accessibility_controls .access-button {
    display: inline-block;
    vertical-align: middle;
    margin: 0 .15em;
    border: 1px solid #ccc;
    border-radius: 3px;
    text-align: center;
    background: -moz-linear-gradient(top, rgba(254,255,232,0) 0%, rgba(214,219,191,0.3) 100%) !important; /* FF3.6+ */
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(254,255,232,0)), color-stop(100%,rgba(214,219,191,0.3))) !important; /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(top, rgba(254,255,232,0) 0%,rgba(214,219,191,0.3) 100%) !important; /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(top, rgba(254,255,232,0) 0%,rgba(214,219,191,0.3) 100%) !important; /* Opera11.10+ */
    background: -ms-linear-gradient(top, rgba(254,255,232,0) 0%,rgba(214,219,191,0.3) 100%) !important; /* IE10+ */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#00feffe8\', endColorstr=\'#4dd6dbbf\',GradientType=0 ) !important; /* IE6-9 */
    background: linear-gradient(top, rgba(254,255,232,0) 0%,rgba(214,219,191,0.3) 100%) !important; /* W3C */

    /*disable selection*/
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    
    line-height: normal;
    
}

#accessibility_controls .access-button:hover {
    background: -moz-linear-gradient(top, rgba(254,255,232,0) 0%, rgba(214,219,191,0.5) 50%) !important; /* FF3.6+ */
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(254,255,232,0)), color-stop(50%,rgba(214,219,191,0.5))) !important; /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(top, rgba(254,255,232,0) 0%,rgba(214,219,191,0.5) 50%) !important; /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(top, rgba(254,255,232,0) 0%,rgba(214,219,191,0.5) 50%) !important; /* Opera11.10+ */
    background: -ms-linear-gradient(top, rgba(254,255,232,0) 0%,rgba(214,219,191,0.5) 50%) !important; /* IE10+ */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#00feffe8\', endColorstr=\'#80d6dbbf\',GradientType=0 ) !important; /* IE6-9 */
    background: linear-gradient(top, rgba(254,255,232,0) 0%,rgba(214,219,191,0.5) 50%) !important; /* W3C */

}


#accessibility_controls .access-button .disabled {
    opacity:0.3;
    cursor:not-allowed;;
}

#accessibility_controls .access-button a {
    display: block;
    cursor: pointer;
    color: #000;
    border-radius: 2px;
    padding: .15em .3em;
    min-width: 1.5em;
    text-decoration: none;
}

#accessibility_controls .access-button a:hover {
    text-decoration: none;
}


#accessibility_controls .access-button:active, #accessibility_controls .access-button:focus {
    border: 1px solid #F24602
}

#accessibility_controls .access-button img {
    vertical-align: middle;
}

#accessibility_controls input {
    margin: 0 .2em;
}

#accessibility_controls ul {
    list-style: none;
    margin: .4em 0;
    padding: 0;
}

';

// The display:inline-block CSS declaration is not applied to block's buttons because IE7 doesn't support it.
// Float is used insted for IE7 only.
if (preg_match('/(?i)msie [1-7]/', $_SERVER['HTTP_USER_AGENT'])) {
    echo '#accessibility_controls .access-button{float:left;}';
    echo '.atbar-always{float:left;}';
}
