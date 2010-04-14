<?php
// Generated: $d$ by $id$
// ----------------------------------------------------------------------
// POST-NUKE Content Management System
// Copyright (C) 2001 by the Post-Nuke Development Team.
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
// Original Author of file: Everyone
// Purpose of file: Translation files
// Translation team: Read credits in /docs/CREDITS.txt
// ----------------------------------------------------------------------

// Main Admin Menu Photos

$GLOBALS['imageformat'] = array('0' => 'Keep format',
                                '1' => 'JPEG',
                                '2' => 'PNG');

define('_PHOTO_NOAUTH','You are not authorized to perform that action');

define('_PHOTO_NOSUCHPHOTO','No such photo found in database');

define('_PHOTO_TITLE','PhotoGallery');
define('_PHOTO_ADMIN_TITLE','Administration');
define('_PHOTO_MAIN','Main');
define('_PHOTO_ADDPHOTO','Add Photo');
define('_PHOTO_ADDGALLERY','Add Gallery');
define('_PHOTO_UPDATECONFIG','Update Configuration');
define('_PHOTO_NUMPRODUCTS','# Photos');

define('_PHOTO_IMAGEUPLOAD','Image Upload');
define('_PHOTO_UPLOAD','Upload');
define('_PHOTO_IMAGEUPLOADED','Image Uploaded');
define('_PHOTO_NOIMAGE','No Image Selected');
define('_PHOTO_IMG_NOFILESELECTED','No image file selected');

$GLOBALS['pg_cat_sort'] = array ('0' => 'by date (newest first)',
                                 '1' => 'by date (oldest first)',
 								 '2' => 'alphabetically',
 								 '3' => 'ordered');		
																	
// Global
define('_PHOTO_ID','ID#');
define('_PHOTO_ORDER','Order');
define('_PHOTO_EDIT','Edit');
define('_PHOTO_CANCEL','Cancel');
define('_PHOTO_CONFIRM','Confirm');
define('_PHOTO_DELETE','Delete');
define('_PHOTO_ACTIVE','Active');
define('_PHOTO_GALLERYDESC','Gallery Description');
define('_PHOTO_ACTIVATE','Activate');
define('_PHOTO_DEACTIVATE','Deactivate');

// Move order menu notices
define('_PHOTO_GALLERYACTIVATED','Gallery activated');
define('_PHOTO_GALLERYDEACTIVATED','Gallery deactivated');
define('_PHOTO_PHOTOACTIVATED','Photo activated');
define('_PHOTO_PHOTODEACTIVATED','Photo deactivated');
define('_PHOTO_GALLERYMOVEDUP','Gallery moved up');
define('_PHOTO_GALLERYMOVEDDOWN','Gallery moved down');
define('_PHOTO_PHOTOMOVEDUP','Photo moved up');
define('_PHOTO_PHOTOMOVEDDOWN','Photo moved down');
define('_PHOTO_GALLERYMOVEDTOTOP','Gallery moved to top of list');
define('_PHOTO_GALLERYMOVEDTOBOTTOM','Gallery moved to bottom of list');
define('_PHOTO_PHOTOMOVEDTOTOP','Photo moved  to top of list');
define('_PHOTO_PHOTOMOVEDTOBOTTOM','Photo moved to bottom of list');
define('_PHOTO_MOVEUP','Move up');
define('_PHOTO_MOVEDOWN','Move down');
define('_PHOTO_MOVETOTOP','Move to top');
define('_PHOTO_MOVETOBOTTOM','Move to bottom');
define('_PHOTO_MOVE_NONEABOVE','Photo is already at the top of the list');
define('_PHOTO_MOVE_NONEBELOW','Photo is already at the bottom of the list');

// Gallery Main Menu
define('_PHOTO_EDITCATEGORIES','Edit Galleries');
define('_PHOTO_GALLERYNAME','Gallery Name');
define('_PHOTO_NOCATEGORIESDEFINED','No photo galleries defined.');
define('_PHOTO_GALLERYCREATED','Gallery successfully created');
define('_PHOTO_GALLERYUPDATED','Gallery successfully updated');
define('_PHOTO_GALLERYDELETED','Gallery successfully deleted');


// Photo Edit Menu
define('_PHOTO_PHOTONAME','Photo Name');
define('_PHOTO_PHOTODESC','Photo Description');
define('_PHOTO_PHOTOGALLERY','Photo Gallery');
define('_PHOTO_USEASCATTHUMB','Use as Category Thumbnail');
define('_PHOTO_PHOTOIMAGE','Photo Image');

define('_PHOTO_PHOTONAMEDESC','(required) Enter a name for this photo.');
define('_PHOTO_PHOTODESCDESC','(optional, but recommended) Describe the photo with a brief caption.  Line breaks and simple html formatting is recognized.');
define('_PHOTO_PHOTOGALLERYDESC','(required) Select the gallery this photo will go in. Photos can be moved from one gallery to another with this menu.');
define('_PHOTO_USEASCATTHUMBDESC','Check the box above to use this photo\'s thumbnail as the thumbnail image for this gallery category.');
define('_PHOTO_PHOTOIMAGEDESC','(required) Select an image from your computer using the field above. The image must be a JPG or PNG. The image will be rescaled to a large version and thumbnail version based on the settings in the configuration. The size of the image that can be processed is limited by the amount of memory PHP has available for running scripts (memory_limit in php.ini)');
define('_PHOTO_PHOTOIMAGEUPLOADED','An image has been uploaded for this gallery entry - a thumbnail appears below. If you would like to upload another image, use the field below.');
define('_PHOTO_EDITPHOTOS','Edit Photos');
define('_PHOTO_EDITPHOTO','Edit Photo');
define('_PHOTO_ADDPHOTO2CAT','Add Photo to this Gallery');
define('_PHOTO_DELETEPHOTO','Delete Photo');
define('_PHOTO_CONFIRMPHOTODELETE','Are you sure you want to delete this photo? This operation cannot be undone.');
define('_PHOTO_PHOTOCREATED','Photo successfully created');
define('_PHOTO_PHOTOCREATEDNOPIC','Photo entry successfully created - no photo selected for upload');
define('_PHOTO_PHOTOUPDATED','Photo successfully updated');
define('_PHOTO_PHOTODELETED','Photo successfully deleted');
define('_PHOTO_NOPHOTOSINTHISCAT','No photos defined for this gallery.');
define('_PHOTO_NOGALLERY','No galleries are available to post a photo to.');

define('_PHOTO_BATCHDELETENOPHOTO','No photos were selected to be deleted');
define('_PHOTO_BATCHDELETEPHOTO','Delete Selected Photos?');
define('_PHOTO_CONFIRMBATCHPHOTODELETE','Are you sure you want to delete the selected photos? This operation cannot be undone.');
define('_PHOTO_BATCHPHOTODELETED','Selected photos successfully deleted. Total deleted: ');

define('_PHOTO_CHECKALL','Check All');
define('_PHOTO_UNCHECKALL','Uncheck All');


// Gallery Edit Menu
define('_PHOTO_GALLERYDESC','Gallery Description');
define('_PHOTO_GALLERYNAMEDESC','(required) Enter a name for this gallery which describes the photos you plan to place in it.');
define('_PHOTO_GALLERYDESCDESC','(optional, but recommended) Description that appears under the gallery name on your gallery\'s home page. Line breaks and simple html formatting is recognized.');
define('_PHOTO_CAT_SORT','Sort Photos');
define('_PHOTO_CAT_SORTDESC','Select how the photos in this gallery will be sorted. If selecting "ordered" order arrows will appear on the photo list below upon updating.');
define('_PHOTO_ACTIVECATDESC','Indicates whether or not a gallery, and the photos in it, are available for viewing.  Unchecking the box will not delete any photos or categories.  This option is useful for seasonal types of galleries or if you want to not have a gallery available for viewing while you are editing it.');

define('_PHOTO_TEMPLATEOVERRIDE','Override Template?');
define('_PHOTO_TEMPLATEOVERRIDEDESC','(optional) You can override the default templates for the thumbnail and photo detail pages by entering the name of the template(s) you created above. The template(s) must be in the /pntemplates directory of your PhotoGallery module. You can override just one or both of the templates - leave blank if you do not wish to override.');
define('_PHOTO_TNTEMPLATEOVERRIDE','Thumbnail Page');
define('_PHOTO_DETAILTEMPLATEOVERRIDE','Photo Pages');

define('_PHOTO_PHOTOSPERPAGEOVERRIDE','Override # Photos Per Page?');   	
define('_PHOTO_PHOTOSPERPAGEOVERRIDEDESC','(optional) Select from this list if you wish to override the default number of photos displayed for this gallery category.');

define('_PHOTO_NOSPANOVERRIDE','No Override');  

define('_PHOTO_EDITGALLERY','Edit Gallery');
define('_PHOTO_DELETEGALLERY','Delete Gallery');
define('_PHOTO_CONFIRMGALLERYDELETE','Are you sure you want to delete this gallery? All photos in this gallery will also be deleted. This operation cannot be undone.');
define('_PHOTO_DELETECHECKED','Delete Checked Photos');
define('_PHOTO_TOTALPHOTOS','Total Photos');

// Configuration Menu
define('_PHOTO_MODIFYCONFIG','Update Configuration');
define('_PHOTO_GALLERYNAME','Gallery Name');
define('_PHOTO_GALLERYINTRO','Gallery Intro Text');
define('_PHOTO_PHOTOCOLUMNS','Thumbnail Pages # of Columns');
define('_PHOTO_PAGESPAN','Show on one page');
define('_PHOTO_PHOTOSPERPAGE','Photos Per Page');
define('_PHOTO_GALLERYCOLUMNS','Gallery Home # of Columns');
define('_PHOTO_PHOTOSIZE','Photo Width/Height');
define('_PHOTO_THUMBNAILSIZE','Thumbnail Width/Height');
define('_PHOTO_IMAGEQUALITY','JPEG Image Quality');
define('_PHOTO_IMAGEFORMAT','Image Format');
define('_PHOTO_IMAGEPATH','Photo Images Path');


define('_PHOTO_GALLERYNAMEDESC','(required) Enter the name of your gallery.');
define('_PHOTO_GALLERYINTRODESC','(optional, but recommended) Introductory text which appears on the "home page" of your PhotoGallery gallery. Line breaks and simple HTML formatting is recognized.');
define('_PHOTO_PHOTOCOLUMNSDESC','(required) Number of columns the thumbnail pages will appear in.');
define('_PHOTO_PHOTOSPERPAGEDESC','(required) Select the number of photos you want to appear on each gallery listing page.  Page spanning will be used in the event that there are more photos to display.');
define('_PHOTO_GALLERYCOLUMNSDESC','(required) The number of columns displaying gallery names, descriptions and images you want appearing on your gallery\'s home page.  Take into account the size of your gallery images, the width of your Zikula theme, etc...');
define('_PHOTO_PHOTOSIZEDESC','The maximum width or height your photos will be. Images will be automatically scaled to this size upon upload. Images already uploaded will remain at their current size.');
define('_PHOTO_THUMBNAILSIZEDESC','The maximum width or height your photo thumbnails will be. Thumbnails will be automatically generated to be this size. Thumbnails already generated will remain at their current size.');
define('_PHOTO_IMAGEQUALITYDESC','The quality that JPEG images will be saved at when generating resized photos and thumbnails (10-100). Images already generated will remain at their current quality.');
define('_PHOTO_IMAGEFORMATDESC','Format that you want scaled and uploaded images saved in.');
define('_PHOTO_IMAGEPATHDESC','(required) The path on your web server where you will be uploading the images in your gallery.  It should be relative from the root directory of your Zikula installation, with no slash in the front and a trailing forward slash. Remember, directory names are case-sensitive on many web setups.');


define('_PHOTO_CONFIGUPDATED','Gallery Configuration Updated');

// Batch Add Menu
define('_PHOTO_BATCHADD','Batch Add Photos');
define('_PHOTO_BATCHINTRO','Add many photos at a time by uploading them to the <strong>'.pnModGetVar('PhotoGallery', 'imagepath').'</strong> directory of your website. Subdirectories are not allowed and only JPG/PNG images are processed. You can name/describe the images using any text you like plus placeholder variables which will dynamically be inserted. The (case-sensitive) variables are:<ul><li><strong>%f</strong> - original filename</li><li><strong>%c</strong> - friendly name from filename (capitalization added, extension removed, underscores/dashes converted to spaces)</li><li><strong>%g</strong> - filename of rescaled gallery photo (large version)</li><li><strong>%p</strong> - photo id (pid) of picture (unique number)</li><li><strong>%o</strong> - order of picture in gallery (unique number within this gallery)</li><li><strong>%n</strong> - sequential number of picture in batch</li></ul>');
define('_PHOTO_BATCHOUTTRO','Click the submit button only once, and be patient while your photos are being processed.');
define('_PHOTO_PHOTOBATCHGALLERYDESC','Select the gallery that you want all the batch processed photos added to.');
define('_PHOTO_PHOTOBATCHNAME','Batch Photo Name');
define('_PHOTO_PHOTOBATCHNAMEDESC','Enter the name pattern for these imported photos. See above for special dynamic variables.');
define('_PHOTO_PHOTOBATCHDESC','Batch Photo Description');
define('_PHOTO_PHOTOBATCHDESCDESC','Enter the description pattern for these imported photos. See above for special dynamic variables. Line breaks and simple html formatting is recognized.');

define('_PHOTO_PHOTOBATCHCREATED','Batch processing complete. Photos added: ');

define('_PHOTO_PHOTOBATCHHALTED','<b>Batch processing HALTED due to PHP script execution time limit restrictions.</b><br />Click the "Batch Add Photos" button again to continue batch processing of photos. Photos added: ');
define('_PHOTO_PHOTOBATCHNOPHOTOS','No photos available for batch processing');
?>
