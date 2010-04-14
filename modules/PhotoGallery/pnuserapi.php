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


function photogallery_userapi_getallphotos ($args) 
{
    if (!pnSecAuthAction(0, 'PhotoGallery::', '::', ACCESS_READ)) {
        return array();
    }

    $where = "pn_gid = $args[gid]";
    $sort  = '';
    if ($args['cat_sort'] == '0') {
        $sort = 'dateadded DESC';
    } elseif ($args['cat_sort'] == '1') {
        $sort = 'dateadded ASC';
    } elseif ($args['cat_sort'] == '2') { 
        $sort = 'name ASC';
    } else {
        $sort = 'sortorder';
    }

    $photos = DBUtil::selectObjectArray ('photogallery_photos', $where, $sort);
    if ($photos === false) {
        return LogUtil::registerError (_PHOTO_GETFAILED);
    }

    $summary = array();
    foreach ($photos as $k=>$v) { 
        foreach ($v as $kk=>$vv) {
            $summary[$kk][$k] = $vv;
        } 
        if ($v['pid'] == $args['this_pid']) {
            $this_key = $k;
        } 
    } 
    
    // Set counter for total photos and current photo
    $summary['totalphotocount'] = count($photos);
    $summary['thisphotocount']  = $this_key+1;
    
    // Set keys for next and previous photos
    if (isset($summary['pid'][$this_key+1])) {
        $summary['nextkey'] = $this_key+1;
    } 
        
    if (isset($summary['pid'][$this_key-1])) {
        $summary['prevkey'] = $this_key-1;
    } 
    
    return $summary;
}



// Create a select list of available galleries
function photogallery_userapi_galleryselectlist () 
{
    $where = 'pn_active = 1';
    $sort  = 'pn_sortorder';
    return DBUtil::selectFieldArray ('photogallery_galleries', 'name', $where, $sort, false, 'gid');
}

// Create a select list of available photos
function photogallery_userapi_photoselectlist () 
{
    $where = 'pn_active = 1';
    $sort  = 'pn_name, pn_gid';
    return DBUtil::selectFieldArray ('photogallery_photos', 'name', $where, $sort, false, 'pid');
}

