<?php
/**
 * @version       Id: cbxsaveandvisitbtn.php $
 * @package       Joomla
 * @subpackage    Content
 * @copyright     Copyright (C)2010-2015 joomboxr.com. All rights reserved.
 * @license       GNU/GPL, http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @Support       Forum  https://github.com/codeboxrcodehub/cbxsaveandvisitbtn
 */

//to create api key please go here https://www.dropbox.com/developers/apps/create

// no direct access
defined('_JEXEC') or die;

//error_reporting(E_ALL);
//ini_set("display_errors", 1);

/**
 * Editor Google Drive Picker buton
 *
 * @package        Joomla.Plugin
 * @subpackage     Editors-xtd.dropboxchooser
 * @since          1.0
 */
class plgButtonCBXSaveandvisitbtn extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 *
	 * @param       object $subject The object to observe
	 * @param       array  $config  An array that holds the plugin configuration
	 *
	 * @since       1.0
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}

	/**
	 * Google Driver Picker Button
	 *
	 * @return array A two element array of (imageName, textToInsert)
	 */
	public function onDisplay($name)
	{

		$app   = JFactory::getApplication();
		if(!$app->isAdmin()) return '';

		$input = $app->input;


		$doc      = JFactory::getDocument();
		$template = $app->getTemplate();

		$component = $input->getCmd('option');
		$view      = $input->getCmd('view');
		$layout    = $input->getCmd('layout');
		$id        = $input->getInt('id');

		$type 		= intval($this->params->get('type', 0));
		switch($type){
			case 0:
				//visit
				$title = JText::_('Visit');
				break;
			case 1:
				//visit with save
				$title = JText::_('Save & Visit');
				break;
			case 2:
				//visit with save and close
				$title = JText::_('Visit with Save & Close');
				break;
			case 3:
				//visit with close
				$title = JText::_('Visit & Close');
				break;
		}



		if ($component == 'com_content' && $view == 'article' && $layout == 'edit' && $id > 0 && !defined('cbxsaveandvisitbtn'))
		{


			$article_link = '#';
			if($component == 'com_content'){
				//require_once JPATH_ADMINISTRATOR . '/components/com_content/models/article.php';
				require_once JPATH_ADMINISTRATOR . '/components/com_content/models/articles.php';
				require_once JPATH_ADMINISTRATOR . '/components/com_categories/models/categories.php';
				require_once JPATH_SITE . '/components/com_content/helpers/route.php';
				//$content_model = new ContentModelArticle();
				// Get an instance of the generic articles model
				$content_model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
				$content_model->setState('filter.search', 'id:'.$id);
				$item = $content_model->getItems($id);
				if($item) $item = $item[0];
				//$item = $content_model->getItems($id);



				$catid = $item->catid;


				$category_model = JModelLegacy::getInstance('Categories', 'CategoriesModel', array('ignore_request' => true));
				$category_model->setState('filter.search', 'id:'.$catid);
				$category = $category_model->getItems($catid);
				if($category) $category = $category[0];

				/*echo '<pre>';
				print_r($category);
				echo '</pre>';

				exit();*/

				$item->slug        = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;
				$item->catslug     = $category->alias ? ($item->catid . ':' . $category->alias) : $item->catid;
				//$item->parent_slug = $item->parent_alias ? ($item->parent_id . ':' . $item->parent_alias) : $item->parent_id;

				//JFactory::$application = JApplication::getInstance('site');

				//$article_link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language));
				$article_link = ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language);
				//var_dump($article_link);
				/*
				//var_dump($article_link);
				$baseurl = JURI::base();

				//var_dump($baseurl);
				$url2 = str_replace('/administrator', '', $baseurl);
				//var_dump($url2);
				$article_link = Juri::root().str_replace(Juri::root(true).'/administrator/', '', $article_link);
				// Set the appilcation back to the administrator app


				var_dump($article_link);

				//var_dump(Juri::root(true));
				//var_dump(Juri::root());
				//JFactory::$application = JApplication::getInstance('administrator');
				*/
			}

			//var_dump('hi there save and visit');
			define('cbxsaveandvisitbtn', 1); //for zoo it will inject button only once
			// button is not active in specific content components
			$getContent = $this->_subject->getContent($name);

			//adding css and js files for dropbox chooser button
			$doc->addStyleSheet(JURI::root(true) . '/plugins/editors-xtd/cbxsaveandvisitbtn/cbxsaveandvisitbtn/cbxsaveandvisitbtn.css?v=1.0');
			JHtml::_('jquery.framework');



			//$doc->addScriptDeclaration($gdriveobject);
			//$doc->addCustomTag('<script language="javascript" type="text/javascript" >'.$gdriveobject.'</script>');

			$doc->addScript(JURI::root(true) . '/plugins/editors-xtd/cbxsaveandvisitbtn/cbxsaveandvisitbtn/cbxsaveandvisitbtn.js?v=3');

			$button        = new JObject;
			$button->modal = true;
			$button->name  = 'apply';
			//$button->name   = 'arrow-down';
			$button->text = $title;
			$button->link = $article_link;
			$button->rel  = $name;
			$button->modal = false;
			$button->class = 'btn cbxsaveandvisitbtn cbxsaveandvisitbtn'.$type;
			//$button->id    = 'cbxsaveandvisitbtn';
			//$button->devkey = $developerkey;
			//$button->cid    = $clientid;

			return $button;
		}
	}
}

/*
 af
am
ar
bg
bn
ca
cs
da
de
el
en
en-GB
es
es-419
et
eu
fa
fi
fil
fr
fr-CA
gl
gu
hi
hr
hu
id
is
it
iw
ja
kn
ko
lt
lv
ml
mr
ms
nl
no
pl
pt-BR
pt-PT
ro
ru
sk
sl
sr
sv
sw
ta
te
th
tr
uk
ur
vi
zh-CN
zh-HK
zh-TW
zu
 */
