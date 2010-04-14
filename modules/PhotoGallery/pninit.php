<?php
/**
 * PhotoGallery
 *
 * @copyright      (c) PhotoGallery Team
 * @link           http://code.zikula.org/pagemaster/
 * @license        GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package        Zikula_3rdParty_Modules
 * @subpackage     PhotoGallery
 * @originalAuthor Nathan Welch
 * @maintainers    Igor Vancouwenberge, Robert Gasch
 */


function photogallery_init() 
{

    if (!DBUtil::createTable('photogallery_galleries')) {
        return false;
    }

    if (!DBUtil::createTable('photogallery_photos')) {
        return false;
    }

    pnModSetVar('PhotoGallery', 'galleryname', _PHOTO_YOURGALLERYNAME);
    pnModSetVar('PhotoGallery', 'galleryintro', _PHOTO_YOURGALLERYINTRO);
    pnModSetVar('PhotoGallery', 'photosperpage', 15);
    pnModSetVar('PhotoGallery', 'gallerycolumns', 2);
    pnModSetVar('PhotoGallery', 'photocolumns', 3);    
    pnModSetVar('PhotoGallery', 'imagepath', 'images/gallery/');
    pnModSetVar('PhotoGallery', 'photosize', '450');
    pnModSetVar('PhotoGallery', 'thumbnailsize', '100');
    pnModSetVar('PhotoGallery', 'imagequality', '70');    
    pnModSetVar('PhotoGallery', 'imageformat', '0');        

    return true;
}



function photogallery_upgrade() 
{
    $rc = true;
    switch ($oldversion) {
        case '1.0':
        case '1.0.0':
            $rc = DBUtil::changeTable ('photogallery_galleries')
            $rc = $rc && DBUtil::changeTable ('photogallery_photos')
            break;
    }

    return $rc;
}


function photogallery_delete() 
{
    if (!DBUtil::dropTable('photogallery_galleries')) {
        return false;
    } 

    if (!DBUtil::dropTable('photogallery_photos')) {
        return false;
    } 

    pnModDelVar ('PhotoGallery');
    return true;
}

