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

$GLOBALS['imageformat'] = array('0' => 'Hou het formaat',
                                '1' => 'JPEG',
                                '2' => 'PNG');

define('_PHOTO_NOAUTH','U bent niet gemachtiged deze actie te ondernemen');

define('_PHOTO_NOSUCHPHOTO','Deze foto niet gevonden in de database');

define('_PHOTO_TITLE','Fotogallerij');
define('_PHOTO_ADMIN_TITLE','Administratie');
define('_PHOTO_MAIN','Main');
define('_PHOTO_ADDPHOTO','Foto toevoegen');
define('_PHOTO_ADDGALLERY','Gallerij toevoegen');
define('_PHOTO_UPDATECONFIG','Configuratie updaten');
define('_PHOTO_NUMPRODUCTS','# Foto\'s');

define('_PHOTO_IMAGEUPLOAD','Afbeelding Upload');
define('_PHOTO_UPLOAD','Upload');
define('_PHOTO_IMAGEUPLOADED','Afbeelding Uploaded');
define('_PHOTO_NOIMAGE','Geen afbeeldingen geselecteerd');
define('_PHOTO_IMG_NOFILESELECTED','Geen afbeeldingsbestand geselecteerd');
define('_PHOTO_IMG_NOTRIGHTTYPE','Niet toegestaan. Toegestane bestandstypen zijn JPEG en PNG images. Geselecteerd bestand was ');
define('_PHOTO_IMG_FILETOOLARGE','Afbeelding te groot. Maximale grootte toegestaan is');
define('_PHOTO_IMG_COULDNTUPLOAD','Image could not be uploaded. Make sure the gallery images directory is writable by your webserver (CHMOD 666 or 777).');

$GLOBALS['pg_cat_sort'] = array ('0' => 'datum (nieuwste eerst)',
                                 '1' => 'datum (oude eerst)',
 								 '2' => 'alfabetisch',
 								 '3' => 'ordered');		
																	
// Global
define('_PHOTO_ID','ID#');
define('_PHOTO_ORDER','Order');
define('_PHOTO_EDIT','Edit');
define('_PHOTO_CANCEL','Annuleer');
define('_PHOTO_CONFIRM','Bevestig');
define('_PHOTO_DELETE','Verwijder');
define('_PHOTO_ACTIVE','Is actief');
define('_PHOTO_GALLERYDESC','Gallerij beschrijving');
define('_PHOTO_ACTIVATE','Activeer');
define('_PHOTO_DEACTIVATE','Deactieer');

// Move order menu notices
define('_PHOTO_GALLERYACTIVATED','Gallerij ge-activeerd');
define('_PHOTO_GALLERYDEACTIVATED','Gallerij niet ge-activeerd');
define('_PHOTO_PHOTOACTIVATED','Afbeelding ge-activeerd');
define('_PHOTO_PHOTODEACTIVATED','Afbeelding niet ge-activeerd');
define('_PHOTO_GALLERYMOVEDUP','Gallerij naar beneden gebracht');
define('_PHOTO_GALLERYMOVEDDOWN','Gallerij naar boven gebracht');
define('_PHOTO_PHOTOMOVEDUP','Foto naar beneden gebracht');
define('_PHOTO_PHOTOMOVEDDOWN','Afbeelding naar boven gebracht');
define('_PHOTO_GALLERYMOVEDTOTOP','Gallerij gewijzigd als eerste');
define('_PHOTO_GALLERYMOVEDTOBOTTOM','Gallerij gewijzigd als laatste');
define('_PHOTO_PHOTOMOVEDTOTOP','Photo moved  to top of list');
define('_PHOTO_PHOTOMOVEDTOBOTTOM','Photo moved to bottom of list');
define('_PHOTO_MOVEUP','Naar boven');
define('_PHOTO_MOVEDOWN','Naar beneden');
define('_PHOTO_MOVETOTOP','Zet als eerste');
define('_PHOTO_MOVETOBOTTOM','Zet als laatste');
define('_PHOTO_MOVE_NONEABOVE','Afbeelding staat reeds als eerste');
define('_PHOTO_MOVE_NONEBELOW','Afbeelding staat reeds als laatste');

// Gallery Main Menu
define('_PHOTO_EDITCATEGORIES','Edit Gallerijen');
define('_PHOTO_GALLERYNAME','Gallerij Naam');
define('_PHOTO_NOCATEGORIESDEFINED','Geen afbeelding gallerij gedefinieerd.');
define('_PHOTO_GALLERYCREATED','Gallerij met succes aangemaakt');
define('_PHOTO_GALLERYUPDATED','Gallerij met succes aangepast');
define('_PHOTO_GALLERYDELETED','Gallerij met succes verwijderd');


// Photo Edit Menu
define('_PHOTO_PHOTONAME','Naam afbeelding');
define('_PHOTO_PHOTODESC','Beschrijving afbeelding');
define('_PHOTO_PHOTOGALLERY','Gallerij afbeeldingen');
define('_PHOTO_USEASCATTHUMB','Gebruik deze als thumbnail');
define('_PHOTO_PHOTOIMAGE','Afbeeldingsgrootte');

define('_PHOTO_PHOTONAMEDESC','(verplicht) Zet een naam voor de afbeelding.');
define('_PHOTO_PHOTODESCDESC','(optioneel, maar toch aangewezen) Beschrijf de foto met een sumiere beschrijving.  Line breaks en simpele html formatering wordt herkend [let dus op wat je doet].');
define('_PHOTO_PHOTOGALLERYDESC','(verplicht) Selecteer een gallerij voor deze afbeelding. Afbeeldingen kunnen verplaatst worden van de ene gallerij naar de andere via dit menu.');
define('_PHOTO_USEASCATTHUMBDESC','Klik de checkbox aan om deze afbeelding als thumbnail te gebruiken als afbeelding voor de gallerij.');
define('_PHOTO_PHOTOIMAGEDESC','(verplicht) Selecteer een afbeelding van uw computer gebruik makende van het veld hierboven. Afbeelding moet de extensie JPG of PNG hebben. De afbeelding zal herschaald worden naar een grote versie en een thumbnail versie naar gelang de configuratie. The size of the image that can be processed is limited by the amount of memory PHP has available for running scripts (memory_limit in php.ini)');
define('_PHOTO_PHOTOIMAGEUPLOADED','Een afbeelding werd opgeladen voor deze gallerij, een tumbnail komt hier onder tevoorschijn. Wilt u een andere afbeelding uploaden, gebruik het veld hieronder.');
define('_PHOTO_EDITPHOTOS','Editeer Afbeeldingen');
define('_PHOTO_EDITPHOTO','Editeer Afbeelding');
define('_PHOTO_ADDPHOTO2CAT','Voeg afbeeldingen toe aan deze gallerij');
define('_PHOTO_DELETEPHOTO','Afbeelding verwijderen');
define('_PHOTO_CONFIRMPHOTODELETE','Are you sure you want to delete this photo? This operation cannot be undone.');
define('_PHOTO_PHOTOCREATED','Afbeelding met succes gecreëerd');
define('_PHOTO_PHOTOUPDATED','Afbeelding met susses aangepast');
define('_PHOTO_PHOTODELETED','Afbeelding met succes verwijderd');
define('_PHOTO_NOPHOTOSINTHISCAT','Geen afbeeldingen gedefinieerd voor deze gallerij.');
define('_PHOTO_NOGALLERY','Geen gallerijen aanwezig om een afbeelding in te plaatsen.');

define('_PHOTO_BATCHDELETENOPHOTO','Er werden geen afbeeldingen geselecteerd om te verwijderen');
define('_PHOTO_BATCHDELETEPHOTO','Geselecteerde afbeeldingen verwijderen?');
define('_PHOTO_CONFIRMBATCHPHOTODELETE','Bent u zeker dat de geselecteerde afbeeldingen mogen verwijderd worden? Dit kan niet ongedaan gemaakt worden.');
define('_PHOTO_BATCHPHOTODELETED','Geselecteerde afbeeldingen met succes verwijderd. In totaal: ');

define('_PHOTO_CHECKALL','Selecteer alles');
define('_PHOTO_UNCHECKALL','Deselecteer alles');


// Gallery Edit Menu
define('_PHOTO_GALLERYDESC','Gallerij beschrijving');
define('_PHOTO_GALLERYNAMEDESC','[VERPLICHT] Gelieve een naam in te geven om deze gallerij te beschrijven.');
define('_PHOTO_GALLERYDESCDESC','[OPTIONEEL, doch aangeraden] Beschrijving dat zal verschijnen onder de gallerij. HTML en \'line breaks\' worden herkend.');
define('_PHOTO_CAT_SORT','Sorteer afbeeldingen');
define('_PHOTO_CAT_SORTDESC','Selecteer hoe de afbeeldingen in de gallerij worden gesorteerd.');
define('_PHOTO_ACTIVECATDESC','Ínpliceet wanneer de gallerij, en de afbeeldingen erin, klaar is om bekeken te worden. Deselecteren van de checkbox zal niet leiden tot het verwijderen van afbeeldingen of gallerijen.');

define('_PHOTO_TEMPLATEOVERRIDE','Override Template?');
define('_PHOTO_TEMPLATEOVERRIDEDESC','(optional) You can override the default templates for the thumbnail and photo detail pages by entering the name of the template(s) you created above. The template(s) must be in the /pntemplates directory of your PhotoGallery module. You can override just one or both of the templates - leave blank if you do not wish to override.');
define('_PHOTO_TNTEMPLATEOVERRIDE','Thumbnail Page');
define('_PHOTO_DETAILTEMPLATEOVERRIDE','Photo Pages');

define('_PHOTO_PHOTOSPERPAGEOVERRIDE','# Afbeeldingen per pagina?');   	
define('_PHOTO_PHOTOSPERPAGEOVERRIDEDESC','[OPTIONEEL] Selecteer van de lijst het default nummer van afbeeldingen in de gallerij.');

define('_PHOTO_NOSPANOVERRIDE','No Override');  

define('_PHOTO_EDITGALLERY','Editeer Gallerij');
define('_PHOTO_DELETEGALLERY','Verwijder Gallerij');
define('_PHOTO_CONFIRMGALLERYDELETE','Bent u zeker om deze gallerij te verwijderen? Alle afbeeldingen zullen ook verwijderd worden. Dit kan niet ongedaan gemaakt worden!');
define('_PHOTO_DELETECHECKED','Verwijder geselecteerde afbeeldingen');
define('_PHOTO_TOTALPHOTOS','Totaal aantal afbeeldingen');

// Configuration Menu
define('_PHOTO_MODIFYCONFIG','Configuratie aanpassen');
define('_PHOTO_GALLERYNAME','Gallerij Naam');
define('_PHOTO_GALLERYINTRO','Gallerij intro tekst');
define('_PHOTO_PHOTOCOLUMNS','Thumbnail pagina\'s # kolommen');
define('_PHOTO_PAGESPAN','Toon op 1 pagina');
define('_PHOTO_PHOTOSPERPAGE','Afbeeldingen per pagina');
define('_PHOTO_GALLERYCOLUMNS','Gallerij hoofdpagina # kolommen');
define('_PHOTO_PHOTOSIZE','Afbeelding Breedte/Hoogte');
define('_PHOTO_THUMBNAILSIZE','Thumbnail Breedte/Hoogte');
define('_PHOTO_IMAGEQUALITY','JPEG Kwaliteit');
define('_PHOTO_IMAGEFORMAT','Afbeeldingsformaat');
define('_PHOTO_IMAGEPATH','Afbeelding path');


define('_PHOTO_GALLERYNAMEDESC','[VERPLICHT] Geef de naam in van de gallerij.');
define('_PHOTO_GALLERYINTRODESC','[OPTIONEEL, doch aangeraden] Inro tekst die verschijnt op de "home page" van het fotoboek. "Line breaks" en éénvoudige HTML wordt herkend.');
define('_PHOTO_PHOTOCOLUMNSDESC','[VERPICHT] Nummer van kolommen waar de thumbnails in verschijnen.');
define('_PHOTO_PHOTOSPERPAGEDESC','[VERPLICHT] Selecteer het nummer van het aantal afbeeldingen die u wilt zien verschijnen in de lijst.');
define('_PHOTO_GALLERYCOLUMNSDESC','[VERPLICHT] Het aantal kolommen van de gallerij-namen, beschrijvingen en afbeeldingen op de fotoalbum "home-page".');
define('_PHOTO_PHOTOSIZEDESC','De maximum breedte of hoogte van de afbeeldingen.');
define('_PHOTO_THUMBNAILSIZEDESC','De maximum breedte of hooghte van de thumbnails die automatisch gegenereerd worden. Gegenereerde thumbnails blijven hun huidige grootte behouden.');
define('_PHOTO_IMAGEQUALITYDESC','Kwaliteit van de JPEG afbeeldingen (10-100). Gegenereerde afbeeldingen blijven hun huidige kwaliteit behouden.');
define('_PHOTO_IMAGEFORMATDESC','Formaat waar de geschaalde en opgeladen afbeeldingen worden opgeslagen.');
define('_PHOTO_IMAGEPATHDESC','[VERPLICHT] Pad naar de gallerij waar de afbeeldingen worden opgeslagen. Relatief van de root, geen "/" ervoor, wel erna, let op de schrijfwijzes!');


define('_PHOTO_CONFIGUPDATED','Gallerij Configuratie werd aangepast');

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