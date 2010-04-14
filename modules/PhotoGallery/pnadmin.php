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
                                                                    

function photogallery_admin_main() 
{
    if (!SecurityUtil::checkPermission('PhotoGallery::', "::", ACCESS_READ)) {
        return pnVarPrepHTMLDisplay(_PHOTO_NOAUTH);
    }

    $sort = 'sortorder';
    $permFilter = array();
    $permFilter[] = array ('realm'            =>  0,
                           'component_left'   =>  'PhotoGallery',
                           'component_middle' =>  '',
                           'component_right'  =>  '',
                           'instance_left'    =>  '',
                           'instance_middle'  =>  '',
                           'instance_right'   =>  'gid',
                           'level'            =>  ACCESS_EDIT);

    $galleries = DBUtil::selectObjectArray ('photogallery_galleries', '', $sort, -1, -1, '', $permFilter);

    // get all image counts in 1 SQL statement
    $pntables  = pnDBGetTables();
    $tbl       = $pntables['photogallery_photos'];
    $sql       = 'SELECT ';
    $tSqlArray = array();
    $columns   = array();
    foreach ($galleries as $v) {
        $tSqlArray[] = "(SELECT count(*) FROM $tbl WHERE pn_gid = $v[gid])";
        $columns[]   = $dat['id'];
    }
    $sql    = 'SELECT ' . implode (',', $tSqlArray);
    $res    = DBUtil::executeSQL ($sql);
    $counts = DBUtil::marshallObjects ($res, $columns);
    $cnt    = 0;
    foreach ($counts['0'] as $k=>$v) {
            $galleries[$cnt++]['photocount'] = $v;
    }

    foreach ($galleries as $k=>$v) {
        $galleries[$k]['activeperm'] = (int)pnSecAuthAction(0, 'PhotoGallery:Active:', "::$v[gid]", ACCESS_EDIT);
        $galleries[$k]['orderperm']  = (int)pnSecAuthAction(0, 'PhotoGallery:Order:', "::$v[gid]", ACCESS_EDIT);
        $galleries[$k]['addphoto']   = (int)pnSecAuthAction(0, 'PhotoGallery::', "::$v[gid]", ACCESS_ADD);
    } 

    $pnRender = pnRender::getInstance ('PhotoGallery');
    $pnRender->assign('gallerycount', count($galleries));
    $pnRender->assign('objectArray', $galleries);

    return $pnRender->fetch('photogallery_admin_main.htm');
}


// Modify config page load
function photogallery_admin_modifyconfig() 
{
    if (!SecurityUtil::checkPermission('PhotoGallery::', "::", ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_PHOTO_NOAUTH);
    }

    $prefs = pnModGetVar ('PhotoGallery');
    $pnRender = pnRender::getInstance ('PhotoGallery');
    $pnRender->assign('gallerycolumnslist', range(1,8));
    $pnRender->assign('photoscolumnslist',  range(1,8));
    $pnRender->assign('photosperpagelist',  array_merge(array(_PHOTO_PAGESPAN),range(1,50)));
    $pnRender->assign('imagequalitylist',   array(10,20,30,40,50,55,60,65,70,75,80,85,90,95,100));
    $pnRender->assign('imageformatlist',    $GLOBALS['imageformat']);
    $pnRender->assign('preferences',        $prefs);

    return $pnRender->fetch('photogallery_admin_modifyconfig.htm');
    
}


// Update configuration variables
function photogallery_admin_updateconfig() 
{
    $prefs = FormUtil::getPassedValue ('preferences', array(), 'POST');
    $url   = pnModURL('PhotoGallery', 'admin', 'main');

    if (!SecurityUtil::confirmAuthKey('PhotoGallery')) {
        return LogUtil::registerAuthidError ($url);
    }

    pnModSetVar('PhotoGallery', 'galleryname',    $prefs['galleryname']);
    pnModSetVar('PhotoGallery', 'galleryintro',   $prefs['galleryintro']);
    pnModSetVar('PhotoGallery', 'photosperpage',  $prefs['photosperpage']);
    pnModSetVar('PhotoGallery', 'gallerycolumns', $prefs['gallerycolumns']);
    pnModSetVar('PhotoGallery', 'photocolumns',   $prefs['photocolumns']);
    pnModSetVar('PhotoGallery', 'imagepath',      $prefs['imagepath']);
    pnModSetVar('PhotoGallery', 'photosize',      $prefs['photosize']);
    pnModSetVar('PhotoGallery', 'thumbnailsize',  $prefs['thumbnailsize']);
    pnModSetVar('PhotoGallery', 'imagequality',   $prefs['imagequality']);
    pnModSetVar('PhotoGallery', 'imageformat',    $prefs['imageformat']);

    LogUtil::registerStatus (_PHOTO_CONFIGUPDATED);
    return pnRedirect($url);
}



// Photo edit/add page
function photogallery_admin_editphoto() 
{
    $pid      = (int)FormUtil::getPassedValue ('pid', '', 'POST');
    $gid      = (int)FormUtil::getPassedValue ('gid', '', 'POST');
    $objectid = (int)FormUtil::getPassedValue ('objectid', '', 'POST');

    $pnRender = pnRender::getInstance ('PhotoGallery');

    if ($pid) {
        // If generic identifier exists, override
        if (!empty($objectid)) {
            $pid = $objectid;
        }    
                        
        $photo = pnModAPIFunc('PhotoGallery', 'admin', 'getphoto', $pid);
       
        if (!SecurityUtil::checkPermission('PhotoGallery::', "::$photo[gid]", ACCESS_EDIT)) {
            $url = pnModURL('PhotoGallery', 'admin', 'main');
            LogUtil::registerError (_PHOTO_NOAUTH, null, $url);
        }
        
        // Get image if exists
        $filepath_lg = pnModGetVar('PhotoGallery', 'imagepath')._PHOTO_IMAGEPREFIX.$pid._PHOTO_LARGEIMAGESUFFIX.'.'.$photo['image'];
        $filepath_th = pnModGetVar('PhotoGallery', 'imagepath')._PHOTO_IMAGEPREFIX.$pid._PHOTO_SMALLIMAGESUFFIX.'.'.$photo['image'];
        
        if (file_exists($filepath_lg)) {
            $photo['image']        = $filepath_lg;
            $photo['image_th']     = $filepath_th;
            $imageinfo             = @getimagesize($filepath_lg);
            $photo['image_width']  = $imageinfo[0]+20;
            $photo['image_height'] = $imageinfo[1]+30;
        }

        $pnRender->assign('pagetitle', _PHOTO_EDITPHOTO.': '.$photo['photo_name']);
        $pnRender->assign('editphoto', '1');
        $pnRender->assign('formaction', 'updatephoto');
    } else {
        $photo = array ('pid'        => '0',
                        'photo_name' => _PHOTO_PHOTONAME,
                        'active'     => '0',
                        'gid'        => $gid);
                                                                                
        $pnRender->assign('pagetitle', _PHOTO_ADDPHOTO);
        $pnRender->assign('formaction', 'createphoto');
   }    
        
    $pnRender->assign('activeperm', '1');
    $pnRender->assign('deleteperm', '1');
    
    $gallerylist = pnModAPIFunc('PhotoGallery', 'admin', 'galleryselectlist');

    // Bounce if no galleries added yet
    if (!$gallerylist) {
        $url = pnModURL('PhotoGallery', 'admin', 'main');
        LogUtil::registerError (_PHOTO_NOAUTH, null, $url);
    }

    $pnRender->assign('photo', $photo);
    $pnRender->assign('gallerylist', $gallerylist);
    $pnRender->assign(pnModGetVar('PhotoGallery'));

    return $pnRender->fetch('photogallery_admin_editphoto.htm');
}


// Create a new photo
function photogallery_admin_createphoto() 
{
    $authid     = FormUtil::getPassedValue ('authid', '', 'POST');
    $gid        = (int)FormUtil::getPassedValue ('gid', '', 'POST');
    $photo_name = FormUtil::getPassedValue ('photo_name', '', 'POST');
    $desc       = FormUtil::getPassedValue ('desc', '', 'POST');
    $active     = (int)FormUtil::getPassedValue ('active', '', 'POST');
    $image      = FormUtil::getPassedValue ('image', '', 'POST');
    $uploadpic  = $_FILES['uploadpic'];

    if (!SecurityUtil::confirmAuthKey('PhotoGallery')) {
        $url = pnModURL('PhotoGallery', 'admin', 'main');
        return LogUtil::registerAuthidError ($url);
    }

    $pid = pnModAPIFunc('PhotoGallery', 'admin', 'createphoto', array('gid'        => $gid,
                                                                      'photo_name' => $photo_name,
                                                                      'desc'       => $desc,
                                                                      'active'     => $active,
                                                                      'image'      => $image,
                                                                      'authid'     => $authid,
                                                                      'uploadpic'  => $uploadpic));
                                                                                                                                
    if ($photo_pid)  {
        if ($uploadpic['name'])
            LogUtil::registerStatus (_PHOTO_PHOTOCREATED);
        else
            LogUtil::registerError (_PHOTO_PHOTOCREATEDNOPIC);
    } else {
        return pnRedirect(pnModURL('PhotoGallery', 'admin', 'editgallery', array('gid'    => $gid,
                                                                                 'authid' => $authid)));
    }
        
    $photo = pnModAPIFunc('PhotoGallery', 'admin', 'getphoto', $pid);

    return pnRedirect(pnModURL('PhotoGallery', 'admin', 'editgallery', array('gid'    => $photo['gid'],
                                                                             'authid' => $authid)));
}


// Update a photo
function photogallery_admin_updatephoto() 
{
    $authid     = FormUtil::getPassedValue ('authid', '', 'POST');
    $pid        = (int)FormUtil::getPassedValue ('pid', '', 'POST');
    $gid        = (int)FormUtil::getPassedValue ('gid', '', 'POST');
    $old_gid    = (int)FormUtil::getPassedValue ('old_gid', '', 'POST');
    $photo_name = FormUtil::getPassedValue ('photo_name', '', 'POST');
    $desc       = FormUtil::getPassedValue ('desc', '', 'POST');
    $active     = (int)FormUtil::getPassedValue ('active', '', 'POST');
    $image      = FormUtil::getPassedValue ('image', '', 'POST');
    $objectid   = (int)FormUtil::getPassedValue ('objectid', '', 'POST');
    $uploadpic  = $_FILES['uploadpic'];

    if (!SecurityUtil::confirmAuthKey('PhotoGallery')) {
        $url = pnModURL('PhotoGallery', 'admin', 'main');
        return LogUtil::registerAuthidError ($url);
    }
        
    // If generic identifier exists, override
    if (!empty($objectid)) {
        $pid = $objectid;
    }                

    if (pnModAPIFunc('PhotoGallery', 'admin', 'updatephoto', array('pid'        => $pid,
                                                                   'gid'        => $gid,
                                                                   'old_gid'    => $old_gid,
                                                                   'photo_name' => $photo_name,
                                                                   'desc'       => $desc,
                                                                   'active'     => $active,
                                                                   'image'      => $image,
                                                                   'objectid'   => $objectid,
                                                                   'uploadpic'  => $uploadpic,
                                                                   'authid'     => $authid))) {
        LogUtil::registerStatus (_PHOTO_PHOTOUPDATED);
    }
    
    return pnRedirect(pnModURL('PhotoGallery', 'admin', 'editgallery', array('gid'    => $gid,
                                                                             'authid' => $authid)));
}



// Delete a photo
function photogallery_admin_deletephoto($args) 
{
    $pid          = (int)FormUtil::getPassedValue ('pid', '', 'POST');
    $gid          = (int)FormUtil::getPassedValue ('gid', '', 'POST');
    $objectid     = (int)FormUtil::getPassedValue ('objectid', '', 'POST');
    $confirmation = FormUtil::getPassedValue ('confirmation', '', 'POST');
                                                                                            
    // Extracts args if called by other module
    extract($args);
        
    // If generic identifier exists, override
    if (!empty($objectid)) {
        $pid = $objectid;
    }                                                                                                
                                                                                 
                                                                                 
    $photo = pnModAPIFunc('PhotoGallery', 'admin', 'getphoto', $pid);
    extract($photo);

    if (!$photo) {
        $url = pnModURL('PhotoGallery', 'admin', 'main');
        return LogUtil::registerError (_PHOTO_NOSUCHPHOTO, null, $url);
    }    
        
    extract($photo);

    if (!SecurityUtil::checkPermission('PhotoGallery::', "::$photo[gid]", ACCESS_DELETE)) {
        $url = pnModURL('PhotoGallery', 'admin', 'main');
        return LogUtil::registerError (_PHOTO_NOAUTH, null, $url);
    }                                                                                                 
                                                                                 
    if (empty($confirmation)) {
        $pnRender = pnRender::getInstance ('PhotoGallery');
        $pnRender->assign('pagetitle', _PHOTO_DELETEPHOTO.': '.$photo['photo_name'].' (ID#'.$photo['pid'].')');
        $pnRender->assign('confirmtext', _PHOTO_CONFIRMPHOTODELETE);
        $pnRender->assign('cancelurl', pnModURL('PhotoGallery', 'admin', 'editgallery', array('gid' => $photo['gid'])));
        $pnRender->assign('confirmurl', pnModURL('PhotoGallery', 'admin', 'deletephoto', array('pid' => $photo['pid'],
                                                                                               'gid' => $photo['gid'])));

        return $pnRender->fetch('photogallery_admin_delete.htm');
    }

    if (!SecurityUtil::confirmAuthKey('PhotoGallery')) {
        $url = pnModURL('PhotoGallery', 'admin', 'main');
        return LogUtil::registerAuthidError ($url);
    }

    if (pnModAPIFunc('PhotoGallery', 'admin', 'deletephoto', array('pid' => $photo['pid'],
                                                                   'gid' => $photo['gid']))) {
        return LogUtil::registerStatus (_PHOTO_PHOTODELETED);
    }

    return pnRedirect(pnModURL('PhotoGallery', 'admin', 'editgallery', array('gid'    => $photo['gid'],
                                                                             'authid' => $photo['authid'])));
}        


// Delete checked photos
function photogallery_admin_deletechecked($args) 
{
    $pid          = (int)FormUtil::getPassedValue ('pid', '', 'POST');
    $gid          = (int)FormUtil::getPassedValue ('gid', '', 'POST');
    $confirmation = FormUtil::getPassedValue ('confirmation', '', 'POST');
                                                                                            
    // Extracts args if called by other module
    extract($args);
    
    if (count($pid) == 0) {
        LogUtil::registerError (_PHOTO_BATCHDELETENOPHOTO);
        return pnRedirect(pnModURL('PhotoGallery', 'admin', 'editgallery', array('gid'    => $gid,
                                                                                 'authid' => $authid)));
    }
                                                                                 
    if (empty($confirmation)) {
        $pnRender = pnRender::getInstance ('PhotoGallery');
        $pnRender->assign('pagetitle', _PHOTO_BATCHDELETEPHOTO);
        $pnRender->assign('confirmtext', _PHOTO_CONFIRMBATCHPHOTODELETE);
        $pnRender->assign('cancelurl', pnModURL('PhotoGallery', 'admin', 'editgallery', array('gid' => $gid)));
        $pnRender->assign('confirmurl', pnModURL('PhotoGallery', 'admin', 'deletechecked', array('pid' => $pid,
                                                                                                 'gid' => $gid)));
        return $pnRender->fetch('photogallery_admin_delete.htm');
    }

    if (!SecurityUtil::confirmAuthKey('PhotoGallery')) {
        $url = pnModURL('PhotoGallery', 'admin', 'main');
        return LogUtil::registerAuthidError ($url);
    }
    
        
    if (!SecurityUtil::checkPermission('PhotoGallery::', "::$gid", ACCESS_DELETE)) {
        $url = pnModURL('PhotoGallery', 'admin', 'main');
        return LogUtil::registerError (_PHOTO_NOAUTH, null, $url);
    }

    $deleteCount = 0;
    foreach ($pid as $_pid) {        
        if (pnModAPIFunc('PhotoGallery', 'admin', 'deletephoto', array('pid' => $_pid,
                                                                       'gid' => $gid))) {
            return LogUtil::registerStatus(_PHOTO_BATCHPHOTODELETED . ++$deleteCount);
        }
    }
    
    return pnRedirect(pnModURL('PhotoGallery', 'admin', 'editgallery', array('gid'    => $gid,
                                                                             'authid' => $authid)));
}                                                                             




// Gallery edit/add page
function photogallery_admin_editgallery() 
{
    $gid      = (int)FormUtil::getPassedValue ('gid');
    $objectid = (int)FormUtil::getPassedValue ('objectid');
        
    if (!SecurityUtil::checkPermission('PhotoGallery::', "::$gid", ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_PHOTO_NOAUTH);
    }        

    $pnRender = pnRender::getInstance ('PhotoGallery');
        
    if ($gid) {
        // If generic identifier exists, override
        if (!empty($objectid)) {
            $gid = $objectid;
        }        
        
        $gallery = pnModAPIFunc('PhotoGallery', 'admin', 'getgallery', $gid);
        $pnRender->assign('pagetitle', _PHOTO_EDITGALLERY.' - '.$gallery['cat_name']);
        $pnRender->assign('editcat', '1');
        $pnRender->assign('formaction', 'updategallery');

        $where = "pn_gid = $gid";
        $sort  = '';
        if ($gallery['cat_sort'] == '0') {
            $sort = 'pn_dateadded DESC';
        } elseif ($gallery['cat_sort'] == '1') {
            $sort = 'pn_dateadded ASC';
        } elseif ($gallery['cat_sort'] == '2') { 
            $sort = 'pn_name ASC';
        } else { 
            $sort = 'pn_order';
        }
        $photos = DBUtil::selectObjectArray ('photogallery_photos', $where, $sort);

        $data = array();
        foreach ($photos as $k=>$photo) {
            $data['pid'][$k]        = $photo['pid'];
            $data['gid'][$k]        = $photo['gid'];
            $data['photo_name'][$k] = $photo['name'];
        } 

        $pnRender->assign('photocount', count($data));
        $pnRender->assign('photo', $data);
    } else {
        $gallery = array ('gid'         => '0',
                          'cat_name'    => _PHOTO_GALLERYNAME,
                          'active'      => '1',
                          'cat_sort'    => '0');
                                                                                
        $pnRender->assign('pagetitle', _PHOTO_ADDGALLERY);
        $pnRender->assign('formaction', 'creategallery');
    }    

    $pnRender->assign('gallery', $gallery);
    $pnRender->assign('cat_sortlist',range(1,8));
    $pnRender->assign('photosperpageoptions',array('-1' => _PHOTO_NOSPANOVERRIDE,'0' => _PHOTO_PAGESPAN));
    $pnRender->assign('photosperpagelist',range(1,50));
    $pnRender->assign('pg_cat_sort',$GLOBALS['pg_cat_sort']);
    $pnRender->assign(pnModGetVar('PhotoGallery'));
    
    // Assign permission flags here instead of inside template as there seems to be a flaw(?)
    if (!SecurityUtil::checkPermission('PhotoGallery::', "::$gid", ACCESS_DELETE)) {
        $pnRender->assign('deleteperm', '1');
    }
    if (!SecurityUtil::checkPermission('PhotoGallery::', "::$gid", ACCESS_EDIT)) {
        $pnRender->assign('activeperm', '1');
    } 
    if (!SecurityUtil::checkPermission('PhotoGallery::', "::$gid", ACCESS_ADD)) {
        $pnRender->assign('addphotoperm', '1');
    }

    return $pnRender->fetch('photogallery_admin_editgallery.htm');
}


// Create a new gallery
function photogallery_admin_creategallery() 
{
    $gid             = (int)FormUtil::getPassedValue ('gid');
    $cat_name        = FormUtil::getPassedValue ('cat_name');
    $desc            = FormUtil::getPassedValue ('desc');
    $cat_sort        = FormUtil::getPassedValue ('cat_sort');
    $active          = (int)FormUtil::getPassedValue ('active');
    $tn_template     = FormUtil::getPassedValue ('tn_template');
    $detail_template = FormUtil::getPassedValue ('detail_template');
    $photosperpage   = (int)FormUtil::getPassedValue ('photosperpage');

    if (!SecurityUtil::confirmAuthKey('PhotoGallery')) {
        $url = pnModURL('PhotoGallery', 'admin', 'main');
        return LogUtil::registerAuthidError ($url);
    }
    
    if ($photosperpage == _PHOTO_NOSPANOVERRIDE) {
        $photosperpage = -1;
    } 

    if (pnModAPIFunc('PhotoGallery', 'admin', 'creategallery', array('gid'             => $gid,
                                                                     'cat_name'        => $cat_name,
                                                                     'desc'            => $desc,
                                                                     'cat_sort'        => $cat_sort,
                                                                     'active'          => $active,
                                                                     'tn_template'     => $tn_template,
                                                                     'detail_template' => $detail_template,
                                                                     'photosperpage'   => $photosperpage))) {
        LogUtil::registerStatus (_PHOTO_GALLERYCREATED);
    }

    return pnRedirect(pnModURL('PhotoGallery', 'admin', 'main'));
}


// Update a gallery
function photogallery_admin_updategallery() 
{
    $gid             = (int)FormUtil::getPassedValue ('gid');
    $cat_name        = FormUtil::getPassedValue ('cat_name');
    $desc            = FormUtil::getPassedValue ('desc');
    $cat_sort        = (int)FormUtil::getPassedValue ('cat_sort');
    $active          = (int)FormUtil::getPassedValue ('active');
    $tn_template     = FormUtil::getPassedValue ('tn_template');
    $detail_template = FormUtil::getPassedValue ('detail_template');
    $photosperpage   = FormUtil::getPassedValue ('photosperpage');
    $objectid        = (int)FormUtil::getPassedValue ('objectid');

    if (!SecurityUtil::confirmAuthKey('PhotoGallery')) {
        $url = pnModURL('PhotoGallery', 'admin', 'main');
        return LogUtil::registerAuthidError ($url);
    }
    
    if ($photosperpage == _PHOTO_NOSPANOVERRIDE) { 
        $photosperpage = -1;
    } 
        
    // If generic identifier exists, override
    if (!empty($objectid)) {
        $gid = $objectid;
    }                

    if (pnModAPIFunc('PhotoGallery', 'admin', 'updategallery', array('gid'             => $gid,
                                                                     'cat_name'        => $cat_name,
                                                                     'desc'            => $desc,
                                                                     'cat_sort'        => $cat_sort,
                                                                     'objectid'        => $objectid, 
                                                                     'active'          => $active,
                                                                     'tn_template'     => $tn_template,
                                                                     'detail_template' => $detail_template,
                                                                     'photosperpage'   => $photosperpage))) {                                                                      

        LogUtil::registerStatus (_PHOTO_GALLERYUPDATED);
    }

    return pnRedirect(pnModURL('PhotoGallery', 'admin', 'main'));
}

// Change gallery status (active/inactive)
function photogallery_admin_changegallerystatus() 
{
    $gid = (int)FormUtil::getPassedValue ('gid');
    $url = pnModURL('PhotoGallery', 'admin', 'main');

    if (!SecurityUtil::confirmAuthKey('PhotoGallery')) {
        return LogUtil::registerAuthidError ($url);
    }

    // need to do this here so we know the gallery status
    $gallery = DBUtil::selectObjectByID ('photogallery_galleries', $gid, 'gid');
    if (!$gallery) {
        return LogUtil::registerStatus ("Unable to retrieve gallery with id [$gid]");
    }

    if (pnModAPIFunc('PhotoGallery', 'admin', 'changegallerystatus', array('gid'    => $gid,
                                                                           'active' => $gallery['active']))) {
        if ($gallery['active']) {
            $message = _PHOTO_GALLERYDEACTIVATED;
        } else { 
            $message = _PHOTO_GALLERYACTIVATED;
        }

        LogUtil::registerStatus ($message);
    }

    return pnRedirect($url);
}


// Change photo status (active/inactive)
function photogallery_admin_changephotostatus() 
{
    $pid = (int)FormUtil::getPassedValue ('pid');
        
    if (!SecurityUtil::confirmAuthKey('PhotoGallery')) {
        $url = pnModURL('PhotoGallery', 'admin', 'main');
        return LogUtil::registerAuthidError ($url);
    }

    if (pnModAPIFunc('PhotoGallery', 'admin', 'changephotostatus', array('pid'    => $pid,
                                                                         'active' => $photo['active']))) {
        if ($photo['active']) {
            $message = _PHOTO_PHOTOACTIVATED;
        } else { 
            $message = _PHOTO_PHOTODEACTIVATED;
        }

        LogUtil::registerStatus ($message);
    }

    $url = pnModURL('PhotoGallery', 'admin', 'editgallery', array('gid' => $photo['gid']));
    return pnRedirect($url);
}


// Delete a gallery
function photogallery_admin_deletegallery() 
{
    $gid          = (int)FormUtil::getPassedValue ('gid');
    $objectid     = (int)FormUtil::getPassedValue ('objectid');
    $confirmation = FormUtil::getPassedValue ('confirmation');
                                                                                            
    // Extracts args if called by other module
    extract($args);
        
    // If generic identifier exists, override
    if (!empty($objectid)) {
        $gid = $objectid;
    }                                                                                                
                                                                                 
                                                                                 
    $photo = pnModAPIFunc('PhotoGallery', 'admin', 'getgallery', $gid);
    extract($photo);

    if (!$photo['gid']) {
        $url = pnModURL('PhotoGallery', 'admin', 'main');
        return LogUtil::registerError (_PHOTO_NOSUCHPHOTO, null, $url);
    }    
        
    if (!SecurityUtil::checkPermission('PhotoGallery::', "::$gid", ACCESS_DELETE)) {
        $url = pnModURL('PhotoGallery', 'admin', 'main');
        return LogUtil::registerError (_PHOTO_NOAUTH, null, $url);
    }                                                                                                 
                                                                                 
    if (empty($confirmation)) {
        $pnRender = pnRender::getInstance ('PhotoGallery');
        $pnRender->assign('pagetitle', _PHOTO_DELETEGALLERY.': '.$cat_name.' (ID#'.$gid.')');
        $pnRender->assign('confirmtext', _PHOTO_CONFIRMGALLERYDELETE);
        $pnRender->assign('cancelurl', pnModURL('PhotoGallery','admin','main'));
        $pnRender->assign('confirmurl', pnModURL('PhotoGallery','admin','deletegallery', array('gid' => $gid)));

        return $pnRender->fetch('photogallery_admin_delete.htm');
    }

    if (!SecurityUtil::confirmAuthKey('PhotoGallery')) {
        $url = pnModURL('PhotoGallery', 'admin', 'main');
        return LogUtil::registerAuthidError ($url);
    }

        
    if (pnModAPIFunc('PhotoGallery', 'admin', 'deletegallery', array('gid' => $gid))) {
        LogUtil::registerStatus (_PHOTO_GALLERYDELETED);
    }        

    return pnRedirect(pnModURL('PhotoGallery', 'admin', 'main'));
}


// ORDERING FUNCTIONS

// Move gallery down
function photogallery_admin_incgallery() 
{
    $gid   = (int)FormUtil::getPassedValue ('gid');
    $tobot = FormUtil::getPassedValue ('tobot');
        
    if ($tobot) {
       $message = _PHOTO_GALLERYMOVEDTOBOTTOM;
    } else {
       $message = _PHOTO_GALLERYMOVEDDOWN;
    }

    if (!SecurityUtil::confirmAuthKey('PhotoGallery')) {
        $url = pnModURL('PhotoGallery', 'admin', 'main');
        return LogUtil::registerAuthidError ($url);
    }

    if (pnModAPIFunc('PhotoGallery', 'admin', 'incgallery', array('gid'   => $gid,
                                                                  'tobot' => $tobot))) {
        LogUtil::registerStatus ($message);
    }

    return pnRedirect(pnModURL('PhotoGallery', 'admin', 'main'));
}


// Move gallery up
function photogallery_admin_decgallery() 
{
    $gid   = (int)FormUtil::getPassedValue ('gid');
    $totop = FormUtil::getPassedValue ('totop');

    if ($totop) {
       $message = _PHOTO_GALLERYMOVEDTOTOP;
    } else {
       $message = _PHOTO_GALLERYMOVEDUP;
    }

    if (!SecurityUtil::confirmAuthKey('PhotoGallery')) {
        $url = pnModURL('PhotoGallery', 'admin', 'main');
        return LogUtil::registerAuthidError ($url);
    }

    if (pnModAPIFunc('PhotoGallery', 'admin', 'decgallery', array('gid'   => $gid,
                                                                  'totop' => $totop))) {
        LogUtil::registerStatus ($message);
    }

    return pnRedirect(pnModURL('PhotoGallery', 'admin', 'main'));
}


// Move photo down
function photogallery_admin_incphoto() {

    $gid   = (int)FormUtil::getPassedValue ('gid');
    $pid   = (int)FormUtil::getPassedValue ('pid');
    $tobot = FormUtil::getPassedValue ('tobot');

    if ($tobot) {
       $message = _PHOTO_PHOTOMOVEDTOBOTTOM;
    } else {
       $message = _PHOTO_PHOTOMOVEDDOWN;
    }

    if (!SecurityUtil::confirmAuthKey('PhotoGallery')) {
        $url = pnModURL('PhotoGallery', 'admin', 'main');
        return LogUtil::registerAuthidError ($url);
    }

    if (pnModAPIFunc('PhotoGallery', 'admin', 'incphoto', array('pid'   => $pid,
                                                                'tobot' => $tobot))) {
        LogUtil::registerStatus ($message);
    }

    return pnRedirect(pnModURL('PhotoGallery', 'admin', 'editgallery', array('gid' => $gid)));
}


// Move photo up
function photogallery_admin_decphoto() 
{
    $gid   = (int)FormUtil::getPassedValue ('gid');
    $pid   = (int)FormUtil::getPassedValue ('pid');
    $totop = FormUtil::getPassedValue ('tobot');
        
    if ($totop) {
       $message = _PHOTO_PHOTOMOVEDTOTOP;
    } else {
       $message = _PHOTO_PHOTOMOVEDUP;
    } 

    if (!SecurityUtil::confirmAuthKey('PhotoGallery')) {
        $url = pnModURL('PhotoGallery', 'admin', 'main');
        return LogUtil::registerAuthidError ($url);
    }

    if (pnModAPIFunc('PhotoGallery', 'admin', 'decphoto', array('pid'   => $pid,
                                                                'totop' => $totop))) {
        LogUtil::registerStatus ($message);
    }

    return pnRedirect(pnModURL('PhotoGallery', 'admin', 'editgallery', array('gid' => $gid)));
}



// Batch photo add page
function photogallery_admin_batchadd() 
{
    if (!SecurityUtil::checkPermission('PhotoGallery:Batch:', "::$gid", ACCESS_DELETE) || !SecurityUtil::checkPermission('PhotoGallery::', "::", ACCESS_ADD)) {
        return pnVarPrepHTMLDisplay(_PHOTO_NOAUTH);
    }
                                          
    $gid             = (int)FormUtil::getPassedValue ('gid');
    $photobatch_name = FormUtil::getPassedValue ('photobatch_name');
    $photobatch_desc = FormUtil::getPassedValue ('photobatch_desc');

    $pnRender = pnRender::getInstance ('PhotoGallery');
 
    $gallerylist = pnModAPIFunc('PhotoGallery', 'admin', 'galleryselectlist');

    // Bounce if no galleries added yet
    if (!$gallerylist) {
        LogUtil::registerStatus (_PHOTO_NOGALLERY);
        return pnRedirect(pnModURL('PhotoGallery', 'admin', 'main'));
    }

    $pnRender->assign('gallerylist', $gallerylist);
    $pnRender->assign('gid', $gid);
    $pnRender->assign('photobatch_name', $photobatch_name);
    $pnRender->assign('photobatch_desc', $photobatch_desc);
    $pnRender->assign(pnModGetVar('PhotoGallery'));

    return $pnRender->fetch('photogallery_admin_batchadd.htm');
}


// Batch create new photos
function photogallery_admin_batchaddcreate() 
{
    $authid          = FormUtil::getPassedValue ('authid');
    $gid             = (int)FormUtil::getPassedValue ('gid');
    $photobatch_name = FormUtil::getPassedValue ('photobatch_name');
    $photobatch_desc = FormUtil::getPassedValue ('photobatch_desc');

    if (!SecurityUtil::confirmAuthKey('PhotoGallery')) {
        $url = pnModURL('PhotoGallery', 'admin', 'main');
        return LogUtil::registerAuthidError ($url);
    }

    $photos_added = pnModAPIFunc('PhotoGallery', 'admin', 'batchaddcreate', array('gid'             => $gid,
                                                                                  'photobatch_name' => $photobatch_name,
                                                                                  'photobatch_desc' => $photobatch_desc,
                                                                                  'authid'          => $authid));

    if ($photos_added === 0)  {
        LogUtil::registerStatus (_PHOTO_PHOTOBATCHNOPHOTOS);
    } elseif ($photos_added < 0) {
        LogUtil::registerStatus (_PHOTO_PHOTOBATCHHALTED. "(-$photos_added)");
        return pnRedirect(pnModURL('PhotoGallery', 'admin', 'batchadd', array('gid'             => $gid,
                                                                              'photobatch_name' => $photobatch_name,
                                                                              'photobatch_desc' => $photobatch_desc,
                                                                              'authid'          => $authid)));

    } elseif($photos_added) {
        LogUtil::registerStatus (_PHOTO_PHOTOBATCHCREATED. "$photos_added");
    }

    return pnRedirect(pnModURL('PhotoGallery', 'admin', 'main'));

}

