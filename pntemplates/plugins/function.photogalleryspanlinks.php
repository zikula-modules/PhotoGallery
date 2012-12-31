<?php
// $Id: function.photogalleryspanlinks.php,v 1.5 2005/11/09 10:03:25 nate_02631 Exp $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2002 by the PostNuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// Based on:
// PHP-NUKE Web Portal System - http://phpnuke.org/
// Thatware - http://thatware.org/
// ----------------------------------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

 
/**
 * Smarty function to output page numbers and spanning links
 * 
 * Minimum required parameters:
 * <!--[photogalleryspanlinks photosperpage="5" total="30" startnum="6" gid="1"]-->
 * 
 * @author       Andreas Krapohl
 * @since        10/01/04
 * @see          function.exampleadminlinks.php::smarty_function_exampleadminlinks()
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @param        string      $total       Total number of photos
 * @param        string      $startnum    Item # offset
 * @param        string      $gid         Item Category
 * @param        string      $style       CSS style of link set 
 * @param        string      $align       Alignment of page set, can be "left" "right" or "center"  
 * @param        bool        $viewtext    Show "Viewing Items" text (can be true or false)  
 * @param        bool        $pagetext    Show "Page" text (can be true or false)   
 * @param        string      $text_prev   Text/HTML for "Previous Page" link 
 * @param        string      $text_next   Text/HTML for "Next Page" link
 * @param        string      $separator   Text/HTML for "Next Page" link 
 * NOTE: For text_prev, text_next or separator, you can include HTML (escape any double quotes): <img src=\"images/button_next.gif\" /> 
 * @return       string      the results of the module function
 */

function smarty_function_photogalleryspanlinks($params, &$smarty) {

    extract($params);
	
	 // Don't output span links if none
	 if ($total <= 0 || $photosperpage <= 0)
	    return false;
		
		// Set default values if not set
	  if (!isset($style)) {
		    $style = 'links';
	  }		
		
	  if (!isset($align)) {
		    $align = 'right';
	  }
		
	  if (!isset($txt_prev)) {
		    $txt_prev = '&lt;&lt;';
	  }
		
	  if (!isset($txt_next)) {
		    $txt_next = '&gt;&gt;';
	  }
		
	  if (!isset($separator)) {
		    $separator = '&nbsp;';
	  }		
		
	  if (!isset($viewtext)) {
		    $viewtext = true;
	  }	
		
	  if (!isset($pagetext)) {
		    $pagetext = true;
	  }			
		
		
				
		if ($pagetext)
		    $span_links['0'] = _PHOTO_PAGE.': ';		
				
		for($i = 0; $i < $total; $i+= $photosperpage) {
		
		$pagecount++;
		
		$this_startnum = $i;
		
		$j = $i + $photosperpage;
		
		if ($j > $total)
		    $j = $total;
		
		// Previous Page Link
		if ($i == 0 && $startnum != 0)		
		    $span_links[0] .= '<a href="index.php?module=PhotoGallery&amp;startnum='.($startnum - $photosperpage).'&amp;gid='.$gid.'" class="'.$style.'" style="text-decoration: none;">'.$txt_prev.'</a>&nbsp;&nbsp;';				

		// Next Page Link
		if ($i != $startnum && $i == ($startnum + $photosperpage))		
		    $next_link = '&nbsp;&nbsp;<a href="index.php?module=PhotoGallery&amp;startnum='.$this_startnum.'&amp;gid='.$gid.'" class="'.$style.'" style="text-decoration: none;">'.$txt_next.'</a>';				
				
				
		if ($i != $startnum)
		    $span_links[0] .= '<a href="index.php?module=PhotoGallery&amp;startnum='.$i.'&amp;gid='.$gid.'" class="'.$style.'">';
		else {
		           
				if ((($i+1) == $j) && $viewtext)
				    $span_links[1]= _PHOTO_VIEWINGPHOTOS.' '.$j.' '._PHOTO_OF.' '.$total;	
				elseif ($viewtext)
				    $span_links[1]= _PHOTO_VIEWINGPHOTOS.''._PHOTO_AND.' '.($i+1).'-'.$j.' '._PHOTO_OF.' '.$total;										
		    }
		
		    $span_links[0] .= $pagecount;
		
		if ($i != $startnum)
		    $span_links[0] .= '</a>';

		if ($j != $total)	
		    $span_links[0] .= $separator;		

		}
		
		$span_links[0] .= $next_link;
		
//		$output = '<span class="'.$style.'" style="float: '.$align.'; white-space: nowrap;"><strong>'.$span_links[0].'</strong>';
//		$output = '<p class="'.$style.'">'.$total.' Afbeeldingen';
		
		if ($viewtext && $total > $photosperpage)
		    $output .= '&nbsp;|&nbsp;<strong>'.$span_links[0].'</strong>';
		if ($viewtext && $total > $photosperpage)
		    $output .= '&nbsp;&nbsp;&nbsp;&nbsp;'.$span_links[1];
		$output .= '</p>';

//		$output .= '<hr />'.$j;
//		$output .= '<hr />'.$i;
//		$output .= '<hr />'.$total;
//		$output .= '<hr />'.$photosperpage;
	
		return $output;
}

?>