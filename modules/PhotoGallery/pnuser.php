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


function photogallery_user_main() 
{

    if (!pnSecAuthAction(0, 'PhotoGallery::', '::', ACCESS_READ)) {
        return pnVarPrepHTMLDisplay(_PHOTO_NOAUTH);
    } 
        
    $gid      = (int)FormUtil::getPassedValue ('gid', 0);
    $startnum = (int)FormUtil::getPassedValue ('startnum', -1);

    $pnRender = pnRender::getInstance ('PhotoGallery');
    $pnRender->assign('allcats',pnModAPIFunc('PhotoGallery', 'user', 'galleryselectlist'));
    $pnRender->assign('gid',$gid);
    $pnRender->assign('startnum',$startnum);

    $where  = "pn_gid = $gid AND pn_active = 1";
    $gallery = DBUtil::selectObject ('photogallery_galleries', $where);

    $gSummary = array();
    if (!$gallery) { // No gallery selected or does not exist, so output main store page    
        $where     = "pn_active = 1";
        $galleries = DBUtil::selectObjectCount ('photogallery_galleries', $where);
        if ($galleries === false) {
            return false;
        } 
        foreach ($galleries as $k=>$v) {
            if (!pnSecAuthAction(0, 'PhotoGallery::', "::$v[gid]", ACCESS_READ)) {
                continue;
            }
            $gSummary['gid'][$k]         = $v['gid'];
            $gSummary['galleryname'][$k] = $v['name'];
            $gSummary['desc'][$k]        = $v['desc'];


            $where = "pn_gid = $gid AND pn_active = 1";
            $photo = DBUtil::selectObject('photogallery_photos', $where);
            if ($photo) {
                $gallery['thumbnail'][$k] = $photo['pid'];
                $gallery['image'][$k]     = $photo['image'];
            } 

            $where = "pn_gid = $gid";
            $gallery['numphotos'][$gallerycount-1] = DBUtil::selectObjectCount ('photogallery_photos', $where);

            if (pnSecAuthAction(0, 'PhotoGallery::', "::$gid", ACCESS_EDIT)) {
                $gallery['editlink'][$k] = 1;
            } 
        } 

        $pnRender->assign('columnwidth',intval(100/pnModGetVar('PhotoGallery', 'gallerycolumns')));
        $pnRender->assign('gallerycount',$gCount);
        $pnRender->assign('count',$count);
        $pnRender->assign('gallery',$gSummary);
                        
        // Set page title -> FIXME: move to template code
        $GLOBALS['info']['title'] = pnVarPrepForDisplay(pnModGetVar('PhotoGallery', 'galleryname'));
        return $pnRender->fetch('photogallery_user_main.htm');
    } else { // Gallery exists - fetch photos and display, thumbnail (if exists), photo name, price, buy now button, and link to full photo page
        if (!pnSecAuthAction(0, 'PhotoGallery::', "::$gid", ACCESS_READ)) {
            return pnVarPrepHTMLDisplay(_PHOTO_GALLERYNOTAUTH);
        } 
                     
        $where = "pn_gid = $gid";
        $sort  = '';
        if ($cat_sort == '0') {
            $sort = 'pn_dateadded DESC';
        } elseif ($cat_sort == '1') {
            $sort = 'pn_dateadded ASC';
        } elseif ($cat_sort == '2') {
            $sort = 'pn_name ASC';
        } else {
            $sort = 'pn_sortorder';
        }
        $photoCountGallery = DBUtil::selectObjectCount('photogallery_photos', $where);

        $photosperpage = (int)FormUtil::getPassedValue ('photosperpage');
        // Check if this category is overriding default
        $photosperpage = $gallery['photosperpage']==-1 ? pnModGetVar('PhotoGallery', 'photosperpage') : $gallery['photosperpage'];
        
        $photos = DBUtil::selectObjectArray ('photogallery_photos', $where, $sort, $startnum, $photosperpage);
        if ($photos === false) {
            return false;
        } 

        $editable = (int)pnSecAuthAction(0, 'PhotoGallery::', "::$gid", ACCESS_EDIT);
        foreach ($photos as $k=>$v) {
            $photos[$k]['editable'] = $editable;
	} 

        $pnRender->assign('columnwidth',intval(100/$num_cols));
        $pnRender->assign('gid',$gid);
        $pnRender->assign('photos',$photos);
        $pnRender->assign('photo_count',count($photos));
        $pnRender->assign('galleryname',$galleryname);
        $pnRender->assign('gallerydesc',$gallerydesc);
        $pnRender->assign('num_cols',$num_cols);
        $pnRender->assign('photosperpage',$photosperpage);
        $pnRender->assign('total',$photoCountGallery);
        
        // Set page title: FIXME -> move to template code
        $GLOBALS['info']['title'] = pnVarPrepForDisplay($galleryname);

        // If template override exists, use the file specified, otherwise use default
        if ($tn_template && file_exists('modules/PhotoGallery/pntemplates/photogallery_user_'.$tn_template.'.htm')) {
            return $pnRender->fetch('photogallery_user_'.$tn_template.'.htm');
        } 

        return $pnRender->fetch('photogallery_user_gallerypage.htm');
    }
}



// Photo detail page load
function photogallery_user_detail() 
{
    if (!pnSecAuthAction(0, 'PhotoGallery::', '::', ACCESS_READ)) {
        return pnVarPrepHTMLDisplay(_PHOTO_NOAUTH);
    } 
                
    $pid = (int)FormUtil::getPassedValue ('pid');
    DBUtil::incrementObjectFieldByID('photogallery_photos', 'hits', $pid, 'pid');

    $joinInfo = array();
    $joinInfo[] = array ( 'join_table'          =>  'photogallery_galleries',
                          'join_field'          =>  array ('name', 'desc', 'cat_sort', 'detail_template'),
                          'object_field_name'   =>  array ('galleryname', 'gallerydesc', 'cat_sort', 'detail_template'),
                          'compare_field_table' =>  'gid',
                          'compare_field_join'  =>  'gid');

    $photo = DBUtil::selectExpandedObjectByID ('photogallery_photos', $joinInfo, $pid, 'pid');
    if ($photo === false) {
        return pnVarPrepHTMLDisplay(_PHOTO_ERROR);
    } 
    if (!$photo) {
        return pnVarPrepHTMLDisplay(_PHOTO_NOPHOTOFOUND);
    } 
        
    if (!pnSecAuthAction(0, 'PhotoGallery::', "::$photo[gid]", ACCESS_READ)) {
        return pnVarPrepHTMLDisplay(_PHOTO_NOAUTH);
    } 
                
    $photo['editable'] = (int)pnSecAuthAction(0, 'PhotoGallery::', "::".$photo['gid'], ACCESS_EDIT);
                        
    $pnRender = pnRender::getInstance ('PhotoGallery');
    $pnRender->assign('photo',$photo);
    $pnRender->assign('startnum',pnVarCleanFromInput('startnum'));
    $pnRender->assign('allcats',pnModAPIFunc('PhotoGallery', 'user', 'galleryselectlist'));
    $pnRender->assign('gid',$photo['gid']);
    
    // Uncomment the next two lines to fetch and pass all other photo data from this category to template
    // Useful for making things such as filmstrips, previous/next links, etc...    
    $photolist = pnModAPIFunc('PhotoGallery', 'user', 'getallphotos', array('cat_sort' => $photo['cat_sort'],
                                                                            'gid'      => $photo['gid'],
                                                                            'this_pid' => $photo['pid']));
    $pnRender->assign('photolist',$photolist);

    // Set page title: FIXME -> move to template code
    $GLOBALS['info']['title'] = pnVarPrepForDisplay($photo['photoname']);

    // If template override exists, use the file specified, otherwise use default
    if ($photo['detail_template'] && file_exists('modules/PhotoGallery/pntemplates/photogallery_user_'.$photo['detail_template'].'.htm')) { 
        return $pnRender->fetch('photogallery_user_'.$photo['detail_template'].'.htm');
    } 

    return $pnRender->fetch('photogallery_user_detail.htm');
}

