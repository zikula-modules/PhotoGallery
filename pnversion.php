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

$modversion['name'] = pnVarPrepForDisplay(_PHOTO_MODNAME);
$modversion['version'] = '1.2';
$modversion['displayname'] = pnVarPrepForDisplay(_PHOTO_MODDISPLAYNAME);
$modversion['description'] = pnVarPrepForDisplay(_PHOTO_MODDESCRIPTION);
$modversion['changelog'] = 'docs/changelog.txt';
$modversion['credits'] = 'docs/credits.txt';
$modversion['help'] = 'docs/help.txt';
$modversion['license'] = 'docs/license.txt';
$modversion['official'] = 1;
$modversion['author'] = 'Nathan Welch';
$modversion['contact'] = 'http://www.natewelch.com';
$modversion['admin'] = 1;
$modversion['securityschema'] = array('PhotoGallery:Config:' => '::',
                                      'PhotoGallery:Batch:'  => '::',
                                      'PhotoGallery::'       => '::Gallery ID',
                                      'PhotoGallery:Active:' => '::Gallery ID',
                                      'PhotoGallery:Order:'  => '::Gallery ID');
