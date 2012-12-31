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


function photogallery_photogalleryblock_init() 
{
    pnSecAddSchema('PhotoGalleryblock::', 'Block title::');
}

function photogallery_photogalleryblock_info() 
{
    return array('text_type'      => 'PhotoGallery',
                 'text_type_long' => 'PhotoGallery Photo Block',
                 'allow_multiple' => true,
                 'form_content'   => false,
                 'form_refresh'   => false,
                 'show_preview'   => true);
}



function photogallery_photogalleryblock_display($blockinfo) 
{
    if (!pnSecAuthAction(0, 'PhotoGalleryblock::', "$blockinfo[title]::", ACCESS_READ)) {
        return;
    }
		
    pnModDBInfoLoad('PhotoGallery');

    $vars['photo_text'] = str_replace("\'","'",$vars['photo_text']);			

    $joinInfo = array();
    $joinInfo[] = array ( 'join_table'          =>  'photogallery_galleries',
                          'join_field'          =>  array ('name', 'active'),
                          'object_field_name'   =>  array ('galleryname', 'galleryactive'),
                          'compare_field_table' =>  'gid',
			  'compare_field_join'  =>  'gid');
    $permFilter = array();
    $permFilter[] = array ('realm'            =>  0,
                           'component_left'   =>  'PhotoGallery',
                           'component_middle' =>  '',
                           'component_right'  =>  '',
                           'instance_left'    =>  '',
                           'instance_middle'  =>  '',
                           'instance_right'   =>  'gid',
                           'level'            =>  ACCESS_READ);


    $sort   = '';
    $wheres = array();
    $wheres[] = 'a.active = 1';

    if ($vars['rand_feat'] == 'feat') {
        $wheres[] = "pid = $vars[cat_photo_id]";
    } elseif ($vars['cat_photo_id']) { // Specific gallery selected? 
            $wheres[] = "tbl.gid = $vars[cat_photo_id]";
            $sort     = 'RAND()';
    }

    $photo = DBUtil::selectExpandedObjectArray ('photogallery_photos', $joinInfo, $where, $sort, 0, 1, '', $permFilter);
    if (!$photo) { 
        return '';
    } 

    $pnRender = pnRender::getInstance ('PhotoGallery');
    $pnRender->assign('photo_text', $vars['photo_text'] ? nl2br(trim($vars['photo_text'])) : '');
    $pnRender->assign('show_link', $vars['show_link']);		
    $pnRender->assign('photo', $photo);						
				
    $blockinfo['content'] = $pnRender->fetch('photogallery_block_photogallery.htm');
    return themesideblock($blockinfo);
}


function photogallery_photogalleryblock_update($blockinfo) 
{
    $vars = pnBlockVarsFromContent($blockinfo['content']);

    $vars['rand_feat']    = FormUtil::getPassedValue ('rand_feat');
    $vars['cat_photo_id'] = FormUtil::getPassedValue ('cat_photo_id');
    $vars['photo_id']     = FormUtil::getPassedValue ('photo_id');		
    $vars['cat_id']       = FormUtil::getPassedValue ('cat_id');	
    $vars['desc_chars']   = FormUtil::getPassedValue ('desc_chars', 10);		
    $vars['photo_text']   = FormUtil::getPassedValue ('photo_text');	
    $vars['show_desc']    = FormUtil::getPassedValue ('show_desc');	
    $vars['show_link']    = FormUtil::getPassedValue ('show_link');													
		
    if ($vars['rand_feat'] == 'rand') {
        $vars['cat_photo_id'] = $vars['cat_id'];
    } else {
        $vars['cat_photo_id'] = $vars['photo_id'];
    }
		
    if ($vars['desc_chars'] < 10) {
        $vars['desc_chars'] = 10;		
    } 

    $blockinfo['content'] = pnBlockVarsToContent($vars);

    $pnRender = pnRender::getInstance ('PhotoGallery');
    $pnRender->clear_cache('photogallery_block_photogallery.htm');
	
    return $blockinfo;		
}


function photogallery_photogalleryblock_modify($blockinfo) 
{
    $vars = pnBlockVarsFromContent($blockinfo['content']);

    if (empty($vars['rand_feat'])) {
        $vars['rand_feat'] = 'rand';
        $vars['cat_photo_id'] = '0';
        $vars['show_desc'] = '1';	
        $vars['desc_chars'] = '100';	
        $vars['show_link'] = '1';	
        $vars['photo_text'] = '';		
    }
		
    $pnRender = pnRender::getInstance ('PhotoGallery');
		
    if ($vars['rand_feat'] == 'rand') {
        $pnRender->assign('cat_id', $vars['cat_photo_id']);
    } else {  
        $pnRender->assign('photo_id', $vars['cat_photo_id']);
    }
		
    $vars['photo_text'] = str_replace("\'","'",$vars['photo_text']);
    $pnRender->assign('rand_feat', $vars['rand_feat']);
    $pnRender->assign('show_desc', $vars['show_desc']);
    $pnRender->assign('desc_chars', $vars['desc_chars']);		
    $pnRender->assign('show_link', $vars['show_link']);	
    $pnRender->assign('photo_text', $vars['photo_text']);		
    $pnRender->assign('galleryselectlist', pnModAPIFunc('PhotoGallery', 'user', 'galleryselectlist'));
    $pnRender->assign('photoselectlist', pnModAPIFunc('PhotoGallery', 'user', 'photoselectlist'));
    return $pnRender->fetch('photogallery_block_photogallery_modify.htm');
}

