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


function photogallery_adminapi_createphoto($args) 
{
    //extract($args);

    if (!isset($args['photo_name']) || !isset($args['gid']) || !is_numeric($args['gid'])) {
        return LogUtil::registerError (_MODARGSERROR);
    }

    if (!SecurityUtil::checkPermission('PhotoGallery::', "::$args[gid]", ACCESS_DELETE)) {
        return LogUtil::registerError (_PHOTO_NOAUTH);
    }

    if (!isset($args['pid'])) { // pid is used for batch add 
        $pid = $args['pid'] = DBUtil::selectFieldMax ('photogallery_photos', 'pid', 'MAX') + 1;
    } 

    if (trim($args['uploadpic']['name']) !== '') {
        $imageExt = pnModAPIFunc('PhotoGallery', 'admin', 'makeimage', array('uploadpic' => $args['uploadpic'],
                                                                              'pid'      => $args['pid'],
                                                                              'size'     => 'thumb'));
                                                                  
        $imageExt = pnModAPIFunc('PhotoGallery', 'admin', 'makeimage', array('uploadpic' => $args['uploadpic'],
                                                                              'pid'      => $args['pid'],
                                                                              'size'     => 'large'));
    }

    if (!isset($imageExt) || !$imageExt) {
        return false;
    }

    if (!isset($args['order'])) { // order is used for batch add 
        $where         = "pn_gid = $args[gid]";
        $args['order'] = DBUtil::selectFieldMax('photogallery_photos', 'pid', 'MAX', $where);
    } 

    // FIXME !!
    $args['name']  = $args['photo_name'];
    $args['image'] = $imageExt;
    $rc = DBUtil::insertObject ($args, 'photogallery_photos', 'pid', true);
    if ($rc === false) {
        return LogUtil::registerError (_PHOTO_INSERTPHOTOFAILED);
    }

    // If set as thumbnail, unset others in this gallery
    if ($args['active']) {
        $pntables = pnDBGetTables();
        $tab      = $pntables['photogallery_photos'];
        $col      = $pntables['photogallery_photos_column'];
        $sql      = "UPDATE $tab SET $col[active] = 0 WHERE $col[pid] != $args[pid] AND $col[gid] = $args[gid]";
        $rc = DBUtil::executeSQL ($sql);
    }    
        
    pnModCallHooks('photo', 'create', $args['pid'], array('module' => 'PhotoGallery'));

    return $args['pid'];
}



// Update an photo
function photogallery_adminapi_updatephoto($args) 
{
    //extract($args);

    if (!isset($args['photo_name']) || 
        !isset($args['gid'])        || !is_numeric($args['gid']) || 
        !isset($args['old_gid'])    || !is_numeric($args['old_gid']) || 
        !isset($args['pid'])        || !is_numeric($args['pid'])) {
        return LogUtil::registerError (_MODARGSERROR);
    }

    if (!SecurityUtil::checkPermission('PhotoGallery::', "::$args[gid]", ACCESS_EDIT) || 
        !SecurityUtil::checkPermission('PhotoGallery::', "::$args[old_gid]", ACCESS_EDIT)) {
        return LogUtil::registerError (_PHOTO_NOAUTH);
    }
    
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $pg_photos_table =& $pntable['photogallery_photos'];
    $pg_photos_column =& $pntable['photogallery_photos_column'];

    $image = null;
    if (trim($args['uploadpic']['name']) != '') {
        $currImage = DBUtil::selectFieldByID ('photogallery_photos', 'image', $args['pid'], 'pid');
                                                                              
        $image = pnModAPIFunc('PhotoGallery', 'admin', 'makeimage', array('uploadpic'   => $args['uploadpic'],
                                                                          'pid'         => $args['pid'],
                                                                          'size'        => 'thumb',
                                                                          'current_ext' => $args['currImage']));
                                                                      
        $image = pnModAPIFunc('PhotoGallery', 'admin', 'makeimage', array('uploadpic'   => $args['uploadpic'],
                                                                          'pid'         => $args['pid'],
                                                                          'size'        => 'large',
                                                                          'current_ext' => $args['currImage']));
    } 

    if ($image === false) {
        return false;
    } 

    $args['name'] = $args['photo_name'];
    if ($image) {
        $args['image'] = $image;
    } 

    $rc = DBUtil::updateObject ($args, 'photogallery_photos', '', 'pid');
    if ($rc === false) {
        return LogUtil::registerError (_PHOTO_UPDATEPHOTOFAILED);
    }
        
    // If photo was moved to gallery, make it the last photo in the new gallery
    // And adjust the order of the photos in the old gallery
    if ($args['old_gid'] != $args['gid']) {

        /*     NOT NEEDED ANYMORE DUE TO USING MAX() rather than record-count
        // Get the info for this photo
        $rows = pnModAPIFunc('PhotoGallery', 'admin', 'getphoto', $pid);
        extract($rows);
        
        // Update order of all photos "above" the deleted one
        $sql = "UPDATE $pg_photos_table
                   SET $pg_photos_column[order] = ($pg_photos_column[order] - 1)
                 WHERE $pg_photos_column[order] > '".pnVarPrepForStore($order)."' AND $pg_photos_column[gid] = '".pnVarPrepForStore($old_gid)."'";
                
        $dbconn->Execute($sql);

        if ($dbconn->ErrorNo() != 0) {
            pnSessionSetVar('errormsg', _PHOTO_UPDATEPHOTOFAILED);
            return false;
        }                    
         */

        $where = "pn_gid = $args[gid]";
        $order = DBUtil::selectFieldMax ('photogallery_photos', 'sortorder', 'MAX', $where);

        $data = array();
        $data['pid']   = $args['pid'];
        $data['order'] = $order + 1;
        $rc = DBUtil::updateObject ($data, 'photogallery_photos', '', 'pid');
        if ($rc === false) {
            return LogUtil::registerError (_PHOTO_UPDATEPHOTOFAILED);
        }
    }
    
    // If set as thumbnail, unset others in this gallery
    if ($args['active']) {
        $pntables = pnDBGetTables();
        $tab      = $pntables['photogallery_photos'];
        $col      = $pntables['photogallery_photos_column'];
        $sql      = "UPDATE $tab SET $col[active] = 0 WHERE $col[pid] != $args[pid] AND $col[gid] = $args[gid]";
        $rc = DBUtil::executeSQL ($sql);
    }    
        
    // Let any hooks know that we have updated an photo.
    pnModCallHooks('photo', 'update', $args['pid'], array('module' => 'PhotoGallery'));

    return $args['pid'];
}


// Delete an photo
function photogallery_adminapi_deletephoto($args) 
{
    //extract($args);

    if (!isset($args['pid']) || !is_numeric($args['pid'])) {
        return LogUtil::registerError (_MODARGSERROR);
    }
        
    // Get the info for this photo
    $photo = pnModAPIFunc('PhotoGallery', 'admin', 'getphoto', $args['pid']);

    if (!SecurityUtil::checkPermission('PhotoGallery::', "::$photo[gid]", ACCESS_DELETE)) {
        return LogUtil::registerError (_PHOTO_NOAUTH);
    }

    $rc = DBUtil::deleteObjectByID('photogallery_photos', $args['pid'], 'pid');
    
    // Remove photos for this photo
    @unlink (pnModGetVar('PhotoGallery', 'imagepath')._PHOTO_IMAGEPREFIX.$pid._PHOTO_SMALLIMAGESUFFIX.'.'.$image);
    @unlink (pnModGetVar('PhotoGallery', 'imagepath')._PHOTO_IMAGEPREFIX.$pid._PHOTO_LARGEIMAGESUFFIX.'.'.$image);
        
    if ($rc === false) {
        return LogUtil::registerError (_PHOTO_DELETEPHOTOFAILED);
    }

/* NOT NECESSARY ANYMORE    
    // Update order of all photos "above" the deleted one
    $sql = "UPDATE $pg_photos_table
               SET $pg_photos_column[order] = ($pg_photos_column[order] - 1)
             WHERE $pg_photos_column[order] > '$order' AND $pg_photos_column[gid] = '".pnVarPrepForStore($gid)."'";
                        
    $dbconn->Execute($sql);
        
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _PHOTO_DELETEPHOTOFAILED);
        return false;
    }
 */
        
    // Clear pnRender cached pages for this photo
    $pnRender = pnRender::getInstance ('PhotoGallery');
    $pnRender->clear_cache(null, $args['pid']);
        
    // Let any hooks know that we have deleted an photo.
    pnModCallHooks('photo', 'delete', $args['pid'], array('module' => 'PhotoGallery'));

    return true;
}


// Create a new gallery
function photogallery_adminapi_creategallery($args) 
{
    //extract($args);

    if (!isset($args['cat_name']) || !isset($args['cat_sort']) || !is_numeric($args['cat_sort'])) {
        return LogUtil::registerError (_MODARGSERROR);
    }

    if (!SecurityUtil::checkPermission('PhotoGallery::', "::", ACCESS_ADD)) {
        return LogUtil::registerError (_PHOTO_NOAUTH);
    }

    $order = DBUtil::selectFieldMax ('photogallery_galleries', 'sortorder', 'MAX');

    $args['order'] = $order+1;
    $args['name']  = $args['cat_name'];
    $rc = DBUtil::insertObject ($args, 'photogallery_galleries', 'gid');
    if ($rc === false) {
        return LogUtil::registerError (_PHOTO_CREATEGALLERYFAILED);
    }

    // Let any hooks know that we have created a new gallery.
    pnModCallHooks('gallery', 'create', $args['gid'], array('module' => 'PhotoGallery'));

    return $args['gid'];
}


// Update a gallery
function photogallery_adminapi_updategallery($args) 
{
    //extract($args);

    if (!isset($args['cat_name']) || !isset($args['gid']) || !is_numeric($args['gid']) || !isset($args['cat_sort']) || !is_numeric($args['cat_sort'])) {
        return LogUtil::registerError (_MODARGSERROR);
    }

    if (!SecurityUtil::checkPermission('PhotoGallery::', "::$args[gid]", ACCESS_EDIT)) {
        return LogUtil::registerError (_PHOTO_NOAUTH);
    }

    $args['name'] = $args['cat_name'];
    $rc = DBUtil::updateObject ($args, 'photogallery_galleries', '', 'gid');
    if ($rc === false) {
        return LogUtil::registerError (_PHOTO_CREATEGALLERYFAILED);
    }
        
    // Let any hooks know that we have updated gallery.
    pnModCallHooks('gallery', 'update', $args['gid'], array('module' => 'PhotoGallery'));

    return $args['gid'];
}

// Delete a gallery
function photogallery_adminapi_deletegallery($args) 
{
    //extract($args);

    if (!isset($args['gid']) || !is_numeric($args['gid'])) {
        return LogUtil::registerError (_MODARGSERROR);
    }

    if (!SecurityUtil::checkPermission('PhotoGallery::', "::$args[gid]", ACCESS_DELETE)) {
        return LogUtil::registerError (_PHOTO_NOAUTH);
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
        
    $pg_galleries_table =& $pntable['photogallery_galleries'];
    $pg_galleries_column =& $pntable['photogallery_galleries_column'];
    $pg_photos_table =& $pntable['photogallery_photos'];
    $pg_photos_column =& $pntable['photogallery_photos_column'];
        
    // Get the info for this gallery
    //$rows = pnModAPIFunc('PhotoGallery', 'admin', 'getgallery', $args['gid']);
    //extract($rows);

    $rc = DBUtil::deleteObjectByID('photogallery_galleries', $args['gid'], 'gid');
    if ($rc === false) {
        return LogUtil::registerError (_PHOTO_DELETEGALLERYFAILED);
    }

    /* NOT NEEDED ANYMORE
        // Update order of all galleries "above" the deleted one
    $sql = "UPDATE $pg_galleries_table
               SET $pg_galleries_column[order] = ($pg_galleries_column[order] - 1)
             WHERE $pg_galleries_column[order] > '".pnVarPrepForStore($order)."'";
                        
    $dbconn->Execute($sql);
        
    if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', _PHOTO_DELETEGALLERYFAILED);
        return false;
    }    
     */ 
        
    // Let any hooks know that we have deleted a gallery.
    pnModCallHooks('gallery', 'delete', $args['gid'], array('module' => 'PhotoGallery'));

    $where  = "pn_gid = $args[gid]";
    $photos = DBUtil::selectObjectArray ('photogallery_photos', $where);

    foreach ($photos as $photo) { 
        // Let any hooks know that we have deleted an photo.
        pnModCallHooks('photo', 'delete', $photo['pid'], array('module' => 'PhotoGallery'));

        // Remove photos for this photo
        @unlink ($imagepath._PHOTO_IMAGEPREFIX.$photo['pid']._PHOTO_SMALLIMAGESUFFIX.'.'.$photo['image']);
        @unlink ($imagepath._PHOTO_IMAGEPREFIX.$photo['pid']._PHOTO_LARGEIMAGESUFFIX.'.'.$photo['image']);
    } 

    // Remove photos belonging to this gallery from dbase
    $rc = DBUtil::deleteObjectByID ('photogallery_photos', $args['gid'], 'gid');
    if ($rc === false) {
        return LogUtil::registerError (_PHOTO_DELETEGALLERYFAILED);
    }
        
    // Clear pnRender cached pages for this gallery
    $pnRender = pnRender::getInstance ('PhotoGallery');
    $pnRender->clear_cache(null, $gid);

    return true;
}



// Change status of gallery (inactive/active)
function photogallery_adminapi_changegallerystatus($args) 
{
    //extract($args);

    if (!isset($args['gid']) || !is_numeric($args['gid']) || !isset($args['status'])) {
        return LogUtil::registerError (_MODARGSERROR);
    }

    if (!SecurityUtil::checkPermission('PhotoGallery:Active:', "::$args[gid]", ACCESS_EDIT)) {
        return LogUtil::registerError (_PHOTO_NOAUTH);
    }
        
    $args['active'] = $args['status'] == 'inactive' ? 0 : 1;
    $rc = DBUtil::updateObject ($args, 'photogallery_galleries', '', 'gid');
    if ($rc === false) {
        return LogUtil::registerError (_PHOTO_CHANGECATSTATUSFAILED);
    }
        
    return true;
}


// Change status of photo (inactive/active)
function photogallery_adminapi_changephotostatus($args) 
{
    //extract($args);

    if (!isset($args['pid']) || !is_numeric($args['pid']) || !isset($args['status'])) {
        return LogUtil::registerError (_MODARGSERROR);
    }
        
    // Get the info for this photo
    $photo = pnModAPIFunc('PhotoGallery', 'admin', 'getphoto', $args['pid']);

    if (!SecurityUtil::checkPermission('PhotoGallery:Active:', "::$photo[gid]", ACCESS_EDIT)) {
        return LogUtil::registerError (_PHOTO_NOAUTH);
    }

    $photo['active'] = $args['status'] == 'inactive' ? 0 : 1;
    $rc = DBUtil::updateObject ($photo, 'photogallery_photos', '', 'pid');
    if ($rc === false) {
        return LogUtil::registerError (_PHOTO_CHANGEPHOTOSTATUSFAILED);
    }
        
    return true;
}

// ORDERING FUNCTIONS -- FIXME --- still needs conversion

// Increase gallery position by one or move to bottom
function photogallery_adminapi_incgallery($args) 
{
    extract($args);

    if (!isset($gid) || !is_numeric($gid)) {
        return LogUtil::registerError (_MODARGSERROR);
    }

    if (!SecurityUtil::checkPermission('PhotoGallery:Order:', "::$args[gid]", ACCESS_EDIT)) {
        return LogUtil::registerError (_PHOTO_NOAUTH);
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Get the gallery table & column names
    $pg_galleries_table =& $pntable['photogallery_galleries'];
    $pg_galleries_column =& $pntable['photogallery_galleries_column'];

    $rows = pnModAPIFunc('PhotoGallery', 'admin', 'getgallery', $gid);
    extract($rows);
                
    // Get count of total galleries
    $sql = "SELECT $pg_galleries_column[gid]
              FROM $pg_galleries_table";
                            
    $result = $dbconn->Execute($sql);
    $total_galleries = $result->PO_RecordCount();
    $result->Close();
        
    if ($order >= $total_galleries || $total_galleries == 1) {
        return LogUtil::registerError (_PHOTO_MOVE_NONEBELOW);
    }        
    
    if ($tobot != '1') { // Move gallery down one position 
        
        // Move ahead gallery down one position
        $sql = "UPDATE $pg_galleries_table
                   SET $pg_galleries_column[order] = $order
                 WHERE $pg_galleries_column[order] = ($order + 1)";
        $dbconn->Execute($sql);
        
        // Move selected gallery up one position
        $sql = "UPDATE $pg_galleries_table
                   SET $pg_galleries_column[order] = ($order + 1)
                 WHERE $pg_galleries_column[gid] = '".pnVarPrepForStore($gid)."'";
        $dbconn->Execute($sql);
        
    } else {  // Move gallery to bottom of list
        
        // Move galleries ahead of selected one down one position
        $sql = "UPDATE $pg_galleries_table
                   SET $pg_galleries_column[order] = ($pg_galleries_column[order] - 1)
                 WHERE $pg_galleries_column[order] > '$order'";
        $dbconn->Execute($sql);
        
        // Move selected gallery top bottom of list
        $sql = "UPDATE $pg_galleries_table
                   SET $pg_galleries_column[order] = '$total_galleries'
                 WHERE $pg_galleries_column[gid] = '".pnVarPrepForStore($gid)."'";
        $dbconn->Execute($sql);
        
    }
     
    return true;
}

// Decrease gallery position by one or move to top
function photogallery_adminapi_decgallery($args) 
{
    extract($args);

    if (!isset($gid) || !is_numeric($gid)) {
        return LogUtil::registerError (_MODARGSERROR);
    }

    if (!SecurityUtil::checkPermission('PhotoGallery:Order:', "::$args[gid]", ACCESS_EDIT)) {
        return LogUtil::registerError (_PHOTO_NOAUTH);
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Get the gallery table & column names
    $pg_galleries_table =& $pntable['photogallery_galleries'];
    $pg_galleries_column =& $pntable['photogallery_galleries_column'];

    $rows = pnModAPIFunc('PhotoGallery', 'admin', 'getgallery', $gid);
    extract($rows);
        
    // Get count of total galleries
    $sql = "SELECT $pg_galleries_column[gid]
              FROM $pg_galleries_table";
                            
    $result = $dbconn->Execute($sql);
    $total_galleries = $result->PO_RecordCount();
    $result->Close();

    if ($order == 1 || $total_galleries == 1) {
        return LogUtil::registerError (_PHOTO_MOVE_NONEABOVE);
    }        
        
    if ($totop != '1') { // Move gallery up one position         
    
        // Move behind gallery up one position
        $sql = "UPDATE $pg_galleries_table
                   SET $pg_galleries_column[order] = $order
                 WHERE $pg_galleries_column[order] = ($order - 1)";
        $dbconn->Execute($sql);
        
        // Move selected gallery down one position
        $sql = "UPDATE $pg_galleries_table
                   SET $pg_galleries_column[order] = ($order - 1)
                 WHERE $pg_galleries_column[gid] = '".pnVarPrepForStore($gid)."'";
        $dbconn->Execute($sql);
                
    } else {  // Move gallery to top of list
        
        // Move behind gallerys up one position
        $sql = "UPDATE $pg_galleries_table
                   SET $pg_galleries_column[order] = ($pg_galleries_column[order] + 1)
                 WHERE $pg_galleries_column[order] < '$order'";
        $dbconn->Execute($sql);
        
        // Move selected gallery to first position
        $sql = "UPDATE $pg_galleries_table
                   SET $pg_galleries_column[order] = '1'
                 WHERE $pg_galleries_column[gid] = '".pnVarPrepForStore($gid)."'";
        $dbconn->Execute($sql);
                        
    }
             
    return true;
}


// Increase photo position by one or move to bottom
function photogallery_adminapi_incphoto($args) 
{
    extract($args);

    if (!isset($pid) || !is_numeric($pid)) {
        return LogUtil::registerError (_MODARGSERROR);
    }
        
    // Get the info for this photo
    $photo = pnModAPIFunc('PhotoGallery', 'admin', 'getphoto', $pid);
    //extract($rows);

    if (!SecurityUtil::checkPermission('PhotoGallery:Order:', "::$photo[gid]", ACCESS_EDIT)) {
        return LogUtil::registerError (_PHOTO_NOAUTH);
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Get the photo table & column names        
    $pg_photos_table =& $pntable['photogallery_photos'];
    $pg_photos_column =& $pntable['photogallery_photos_column'];

    $rows = pnModAPIFunc('PhotoGallery', 'admin', 'getphoto', $pid);
    extract($rows);
                
    // Get count of total photos in that gallery
    $sql = "SELECT $pg_photos_column[pid]
              FROM $pg_photos_table
             WHERE $pg_photos_column[gid] = '".pnVarPrepForStore($gid)."'";
                         
    $result = $dbconn->Execute($sql);
    $total_photos = $result->PO_RecordCount();
    $result->Close();
        
    if ($order >= $total_photos || $total_photos == 1) {
        return LogUtil::registerError (_PHOTO_MOVE_NONEBELOW);
    }
        
    if ($tobot != '1') {     // Move photo down one position            
    
        // Move ahead photo down one position
        $sql = "UPDATE $pg_photos_table
                   SET $pg_photos_column[order] = $order
                 WHERE $pg_photos_column[order] = ($order + 1)
                   AND $pg_photos_column[gid] = '".pnVarPrepForStore($gid)."'";
        $dbconn->Execute($sql);
        
        // Move selected photo up one position
        $sql = "UPDATE $pg_photos_table
                   SET $pg_photos_column[order] = ($order + 1)
                 WHERE $pg_photos_column[pid] = '".pnVarPrepForStore($pid)."'";
        $dbconn->Execute($sql);
                                
    } else {      // Move photo to bottom of list
        
        // Move ahead photo down one position
        $sql = "UPDATE $pg_photos_table
                   SET $pg_photos_column[order] = ($pg_photos_column[order] - 1)
                 WHERE $pg_photos_column[order] > '$order'
                   AND $pg_photos_column[gid] = '".pnVarPrepForStore($gid)."'";
        $dbconn->Execute($sql);
                
        // Move selected photo up one position
        $sql = "UPDATE $pg_photos_table
                   SET $pg_photos_column[order] = '$total_photos'
                 WHERE $pg_photos_column[pid] = '".pnVarPrepForStore($pid)."'";
        $dbconn->Execute($sql);
        
    }
        

    return true;
}

// Decrease photo position by one or move to top
function photogallery_adminapi_decphoto($args) 
{
    extract($args);

    if (!isset($pid) || !is_numeric($pid)) {
        return LogUtil::registerError (_MODARGSERROR);
    }
        
    // Get the info for this photo
    $photo = pnModAPIFunc('PhotoGallery', 'admin', 'getphoto', $pid);
    //extract($rows);

    if (!SecurityUtil::checkPermission('PhotoGallery:Order:', "::$photo[gid]", ACCESS_EDIT)) {
        return LogUtil::registerError (_PHOTO_NOAUTH);
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    // Get the photo table & column names        
    $pg_photos_table =& $pntable['photogallery_photos'];
    $pg_photos_column =& $pntable['photogallery_photos_column'];

    $rows = pnModAPIFunc('PhotoGallery', 'admin', 'getphoto', $pid);
    extract($rows);
        
    // Get count of total photos in the gallery
    $sql = "SELECT $pg_photos_column[pid]
              FROM $pg_photos_table
             WHERE $pg_photos_column[gid] = '".pnVarPrepForStore($gid)."'";
                         
    $result = $dbconn->Execute($sql);
    $total_photos = $result->PO_RecordCount();
    $result->Close();

    if ($order == 1 || $total_photos == 1) {
        return LogUtil::registerError (_PHOTO_MOVE_NONEABOVE);
    }    
        
    if ($totop != '1') {      // Move photo up one position             
    
        // Move behind photo up one position
        $sql = "UPDATE $pg_photos_table
                   SET $pg_photos_column[order] = $order
                 WHERE $pg_photos_column[order] = ($order - 1)
                   AND $pg_photos_column[gid] = '".pnVarPrepForStore($gid)."'";
        $dbconn->Execute($sql);
        
        // Move selected photo down one position
        $sql = "UPDATE $pg_photos_table
                   SET $pg_photos_column[order] = ($order - 1)
                 WHERE $pg_photos_column[pid] = '".pnVarPrepForStore($pid)."'";
        $dbconn->Execute($sql);
                
    } else {      // Move photo to top of list     
        
        // Move behind photo up one position
        $sql = "UPDATE $pg_photos_table
                   SET $pg_photos_column[order] = ($pg_photos_column[order] + 1)
                 WHERE $pg_photos_column[order] < '$order'
                   AND $pg_photos_column[gid] = '".pnVarPrepForStore($gid)."'";
        $dbconn->Execute($sql);
        
        // Move selected photo down one position
        $sql = "UPDATE $pg_photos_table
                   SET $pg_photos_column[order] = '1'
                 WHERE $pg_photos_column[pid] = '".pnVarPrepForStore($pid)."'";
        $dbconn->Execute($sql);
    
    }        
             
    return true;
}




// DATA RETREIVAL

// Get information for a photo
function photogallery_adminapi_getphoto($pid) 
{
    if (!SecurityUtil::checkPermission('PhotoGallery::', "::", ACCESS_READ)) {
        return LogUtil::registerError (_PHOTO_NOAUTH);
    }

    return DBUtil::selectObjectByID ('photogallery_photos', $pid, 'pid');
}



// Get information for a gallery
function photogallery_adminapi_getgallery($gid) 
{
    if (!SecurityUtil::checkPermission('PhotoGallery::', "::$gid", ACCESS_READ)) {
        return LogUtil::registerError (_PHOTO_NOAUTH);
    }

    $gallery = DBUtil::selectObjectByID ('photogallery_galleries', $gid, 'gid');
    $gallery['cat_name'] = $gallery['name'];// FIXME!
    return $gallery;
}


// Create a select list of available galleries
function photogallery_adminapi_batchaddcreate($args) 
{
    // Get max time script can execute and time started to prevent timeouts
    $maximum_time = ini_get('max_execution_time');
    $time_start = time();
    
    extract($args);
    
    if (!$photobatch_name) {
        return LogUtil::registerError (_PHOTO_BATCHNONAME);
    }    

    // Get list of files in gallery directory not processed by PhotoGallery
    $photolist    = array();
    $prefixlength = strlen(_PHOTO_IMAGEPREFIX);
    $imagepath    = pnModGetVar('PhotoGallery', 'imagepath');
    $handle       = opendir($imagepath);
    
    // Loop through files an include only jpg, jpeg or png files
    while ($file = readdir($handle)) {
        if ((strtolower(substr($file, -4)) == '.jpg' || strtolower(substr($file, -4)) == '.png' || strtolower(substr($file, -5)) == '.jpeg') && substr($file,0,$prefixlength) != _PHOTO_IMAGEPREFIX) { 
            $photolist[] = $file;
        }
    }
    
    // Return zero photos to process
    if (!$photolist) {
        return 0;
    } 
        
    $photoCount = count($photolist);

    $pid   = DBUtil::selectFieldMax ('photogallery_photos', 'pid', 'MAX') + 1;
    $where = "pn_gid = $args[gid]";
    $order = DBUtil::selectFieldMax('photogallery_photos', 'pid', $where);

    // Loop through filelist to process photos and build query
    foreach ($photolist as $photo) {
        
        // Check if script is nearing timeout - bounce to batch page for another round
        if ((time() - $time_start) > ($maximum_time - 5)) {
            return -$photocount;
        }
        
        $pid++;
        $imageExt = '';
        $uploadpic['tmp_name'] = $imagepath.$photo;
        
        $imageExt = pnModAPIFunc('PhotoGallery', 'admin', 'makeimage', array('uploadpic'  => $uploadpic,
                                                                              'pid'       => $pid,
                                                                              'size'      => 'thumb'));
                                                                  
        $imageExt = pnModAPIFunc('PhotoGallery', 'admin', 'makeimage', array('uploadpic'  => $uploadpic,
                                                                              'pid'       => $pid,
                                                                              'size'      => 'large'));
                                                                              
        // If image rescale was successful, add the image data to the gallery
        if ($imageExt) {

            $photocount++;
            $order++;
            
            // Delete original image
            @unlink($uploadpic['tmp_name']);
            
            // Replace variable names in name/description with dynamic info
            $this_photobatch_name = pnModAPIFunc('PhotoGallery', 'admin', 'batchname', array('output'       => $photobatch_name,
                                                                                             'origfilename' => $photo,
                                                                                             'pid'          => $pid,
                                                                                             'photocount'   => $photocount,
                                                                                             'order'        => $order,
                                                                                             'imageExt'    => $imageExt));

            $this_photobatch_desc = pnModAPIFunc('PhotoGallery', 'admin', 'batchname', array('output'       => $photobatch_desc,
                                                                                             'origfilename' => $photo,
                                                                                             'pid'          => $pid,
                                                                                             'photocount'   => $photocount,
                                                                                             'order'        => $order,
                                                                                             'imageExt'    => $imageExt));

        // FIXME !!!!!!!!!!!!!!!!1    
            $sql = "INSERT INTO $pg_photos_table (
                                $pg_photos_column[pid],            
                                $pg_photos_column[gid],        
                                $pg_photos_column[name],
                                $pg_photos_column[desc],
                                $pg_photos_column[dateadded],                        
                                $pg_photos_column[active],    
                                $pg_photos_column[image],
                                $pg_photos_column[order],
                                $pg_photos_column[hits])
                    VALUES (
                                $pid,
                                '" . pnVarPrepForStore($gid) . "',
                                '" . pnVarPrepForStore($this_photobatch_name) . "',
                                '" . pnVarPrepForStore($this_photobatch_desc) . "',
                                NOW(),                        
                                '0',    
                                '" . pnVarPrepForStore($imageExt) . "',
                                '" . pnvarPrepForStore($order) . "',
                                '0')";
            $dbconn->Execute($sql);

            if ($dbconn->ErrorNo() != 0) {
                pnSessionSetVar('errormsg', _PHOTO_CREATEPHOTOFAILED);
                return false;
            }
            
            // Let any hooks know that we have created a new photo.
            pnModCallHooks('photo', 'create', $pid, array('module' => 'PhotoGallery'));
                    
        } else { // if $imageExt check
            return LogUtil::registerError (_PHOTO_BATCHERROR);
        }                                                                                                                                        
    }
    
    return $photocount;
}

// Replace variables in batch name/description with dynamic info
function photogallery_adminapi_batchname($args) 
{
    // Make "friendly name" from original filename - replace dashes/underscores with a space,
    // capitalize first words and remove file extension
    $friendlyname = str_replace('-',' ',$args['origfilename']);
    $friendlyname = str_replace('_',' ',$friendlyname);
    $friendlyname = substr($friendlyname, 0, strrpos($friendlyname,'.'));
    $friendlyname = ucwords($friendlyname);
   
    $output = str_replace (array('%p',
                                 '%n',
                                 '%o',
                                 '%f',
                                 '%g',
                                 '%c'),
                           array($pid,
                                 $photocount,
                                 $order,
                                 $origfilename,
                                 _PHOTO_IMAGEPREFIX.$pid._PHOTO_LARGEIMAGESUFFIX.'.'.$imageExt,
                                 $friendlyname),
                           $output);
                                 
   
    return $output;
}


function photogallery_adminapi_makeimage($args) 
{
    //extract($args);

    $imageinfo   = getimagesize($args['uploadpic']['tmp_name']);
    $imageformat = pnModGetVar('PhotoGallery', 'imageformat');
    
    if ($imageinfo[2] != '2' && $imageinfo[2] != '3' or !$imageinfo) {
        return LogUtil::registerError (_PHOTO_IMG_NOTRIGHTTYPE);
    }     
    
    if ($imageformat == '1') {
        $imageExt = 'jpg';
    } elseif ($imageformat == '2') {
        $imageExt = 'png';
    } else {
        if ($imageinfo[2] == '2') {
            $imageExt = 'jpg';
        } elseif ($imageinfo[2] == '3') { 
            $imageExt = 'png';
        } 
    }

    if ($size == 'thumb') {
        $filepath    = pnModGetVar('PhotoGallery', 'imagepath')._PHOTO_IMAGEPREFIX.$args['pid']._PHOTO_SMALLIMAGESUFFIX.'.'.$imageExt;
        $oldfilepath = pnModGetVar('PhotoGallery', 'imagepath')._PHOTO_IMAGEPREFIX.$args['pid']._PHOTO_SMALLIMAGESUFFIX.'.'.$current_ext;
        $maxwidth    = pnModGetVar('PhotoGallery', 'thumbnailsize');
    } else {
        $filepath    = pnModGetVar('PhotoGallery', 'imagepath')._PHOTO_IMAGEPREFIX.$args['pid']._PHOTO_LARGEIMAGESUFFIX.'.'.$imageExt;
        $oldfilepath = pnModGetVar('PhotoGallery', 'imagepath')._PHOTO_IMAGEPREFIX.$args['pid']._PHOTO_LARGEIMAGESUFFIX.'.'.$current_ext;
        $maxwidth    = pnModGetVar('PhotoGallery', 'photosize');
    }
    
    $maxheight   = $maxwidth;
    $quality     = pnModGetVar('PhotoGallery', 'imagequality');
    $imagewidth  = $imageinfo[0];
    $imageheight = $imageinfo[1];

    // Calculate maximum size that GD can process based on available memory
    // $source_max_size = round(max(intval(ini_get('memory_limit')), intval(get_cfg_var('memory_limit'))) * 1048576 * 0.20);// 20% of memory_limit
    
    // Return false if not enough memory available to process
    //if (($imagewidth * $imageheight) > $source_max_size)
    //    return false;

    // Calculate proper ratio of image
    if ($imagewidth <= $maxwidth && $imageheight <= $maxheight) { // image width less than maxwidth - no adjustment
        $newimagewidth  = $imagewidth;
        $newimageheight = $imageheight;
    } else { // image width is larger - set width to max width and height to ratio
        if ($imagewidth > $imageheight) {
            $scaleratio     = ($imageheight / $imagewidth);
            $newimagewidth  = $maxwidth;
            $newimageheight = ($maxheight * $scaleratio);
        } else {
            $scaleratio     = ($imagewidth / $imageheight);
            $newimageheight = $maxheight;
            $newimagewidth  = ($maxwidth * $scaleratio);
        }
    }
        
    if ($imageinfo[2] == '2') {
        $src = imagecreatefromjpeg($args['uploadpic']['tmp_name']);
    } else { 
        $src = imagecreatefrompng($args['uploadpic']['tmp_name']);
    }

    // FIXME, debugging
    if ($src === false) {
       prayer ($args);
       exit ('imagecreate failed ...');
    } 

    $tmp_img   = imagecreatetruecolor($newimagewidth,$newimageheight);
    $new_image = imagecopyresampled($tmp_img, $src, 0, 0, 0, 0, $newimagewidth, $newimageheight, $imagewidth, $imageheight);
   
    if ($imageExt == 'jpg') {
        $image_made = @imagejpeg($tmp_img,$filepath,$quality);
    } else { 
        $image_made = @imagepng($tmp_img,$filepath);
    }
    
    if (!$image_made) {
        return LogUtil::registerError (_PHOTO_IMG_COULDNTUPLOAD);
        imagedestroy($src);
        imagedestroy($new_image);
        imagedestroy($image_made);
        imagedestroy($tmp_img);
        return false;
    } 
                         
    imagedestroy($src);
    imagedestroy($new_image);
    imagedestroy($image_made);
    imagedestroy($tmp_img);
                          
    if (file_exists($filepath)) {
        // Remove old image if a different filename/format
        if ($imageExt != $current_ext) {
            @unlink($oldfilepath);
        } 
        return $imageExt;
    } 

    return LogUtil::registerError (_PHOTO_IMG_COULDNTUPLOAD);
}    


// List build functions

// Create a select list of available galleries
function photogallery_adminapi_galleryselectlist() 
{
    $ca   = array ('gid', 'name');
    $sort = 'pn_sortorder';
    $permFilter = array();
    $permFilter[] = array ('realm'            =>  0,
                           'component_left'   =>  'PhotoGallery',
                           'component_middle' =>  '',
                           'component_right'  =>  '',
                           'instance_left'    =>  '',
                           'instance_middle'  =>  '',
                           'instance_right'   =>  'gid',
                           'level'            =>  ACCESS_EDIT);
    $galleries = DBUtil::selectObjectArray ('photogallery_galleries', '', $sort, -1, -1, '', $permFilter, null, $ca);

    // FIXME
    $summary = array();
    foreach ($galleries as $k=>$v) {
        $summary['gid'][$k]      = $v['gid'];
        $summary['cat_name'][$k] = $v['name'];
    } 

    return $summary;
}

