/*
 CBX Save and Visit for Joomla Javascript
 Copyright 2015 @ Joomboxr.com
 Author: Joomboxr Team
 @license	GNU/GPL, http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

jQuery(document).ready(function($) {

    //comment this in live site
    //console.log('welcome to save and visit log');
    $('a.cbxsaveandvisitbtn').on('click', function(event){
        event.preventDefault();
        //console.log('button clicked');
        $link = $(this).attr('href');
        //console.log($link);
        $link = $link.replace('administrator/', '');
        //console.log($link);

        $class = $(this).attr('class').split(/\s+/);
        $class = parseInt($class[2].replace('cbxsaveandvisitbtn', ''));
        //console.log($class);
        //window.open($link, '_blank');

        switch($class){
            case 0:
                //visit
                window.open($link, '_blank');
                break;
            case 1:
                //visit with save
                Joomla.submitbutton('article.apply');
                window.open($link, '_blank');
                break;
            case 2:
                //visit with save and close
                Joomla.submitbutton('article.save');
                window.open($link, '_blank');
                break;
            case 3:
                //visit with close
                Joomla.submitbutton('article.cancel');
                window.open($link, '_blank');
                break;
        }



    });


});

