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


function photogallery_pntables() 
{
    $tables = array();

    $table = DBUtil::getLimitedTablename('photogallery_galleries');
    $tables['photogallery_galleries'] = $table;
    $columns = array ('gid'             => 'pn_gid',
                      'name'            => 'pn_name',
                      'desc'            => 'pn_desc',    
                      'cat_sort'        => 'pn_cat_sort',
                      'active'          => 'pn_active',
                      'tn_template'     => 'pn_tn_template',
                      'detail_template' => 'pn_detail_template',
                      'photosperpage'   => 'pn_photosperpage',
                      'sortorder'       => 'pn_sortorder'); // schema change
    ObjectUtil::addStandardFieldsToTableDefinition ($columns, 'pn_');
    $tables['photogallery_galleries_column'] = $columns;
    $tabledef = array('gid'             => 'I4 NOTNULL PRIMARY AUTO',
                      'name'            => 'C(127) NOTNULL DEFAULT \'\'',
                      'desc'            => 'X NOTNULL DEFAULT \'\'',
                      'cat_sort'        => 'I1 NOTNULL DEFAULT 3',
                      'active'          => 'L NOTNULL DEFAULT 1',
                      'tn_template'     => 'C(64) NOTNULL DEFAULT \'\'',
                      'detail_template' => 'C(64) NOTNULL DEFAULT \'\'',
                      'photosperpage'   => 'I2 NOTNULL DEFAULT 3',
                      'sortorder'       => 'I4 NOTNULL DEFAULT 0');
    ObjectUtil::addStandardFieldsToTableDataDefinition ($tabledef, 'pn_');
    $tables['photogallery_galleries_column_def'] = $tabledef;


    $table = DBUtil::getLimitedTablename('photogallery_photos');
    $tables['photogallery_photos'] = $table;
    $columns = array ('pid'             => 'pn_pid',
                      'gid'             => 'pn_gid',
                      'name'            => 'pn_name',
                      'desc'            => 'pn_desc',    
                      'dateadded'       => 'pn_dateadded',    
                      'active'          => 'pn_active',
                      'image'           => 'pn_image',
                      'sortorder'       => 'pn_sortorder', // schema change
                      'hits'            => 'pn_hits');
    ObjectUtil::addStandardFieldsToTableDefinition ($columns, 'pn_');
    $tables['photogallery_photos_column'] = $columns;
    $tabledef = array('pid'             => 'I4 NOTNULL PRIMARY AUTO',
                      'gid'             => 'I4 NOTNULL DEFAULT 0',
                      'name'            => 'C(127) NOTNULL DEFAULT \'\'',
                      'desc'            => 'X NOTNULL DEFAULT \'\'',
                      'dateadded'       => 'T',
                      'active'          => 'L NOTNULL DEFAULT 1',
                      'image'           => 'C(64) NOTNULL DEFAULT \'\'',
                      'sortorder'       => 'I4 NOTNULL DEFAULT 0',
                      'hits'            => 'I4 NOTNULL DEFAULT 0');
    ObjectUtil::addStandardFieldsToTableDataDefinition ($tabledef, 'pn_');
    $tables['photogallery_photos_column_def'] = $tabledef;

    return $tables;
}

