<?php
/*
 ********************************************************************
 * @name            Face Gallery
 * @version         1.0: comments/view/comments/view.html.php$
 * @since           Joomla 3.0
 * @package         apptha
 * @subpackage      com_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright       Copyright (C) 2013 Powered by Apptha
 * @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
 ********************************************************************
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

// View class for a list of facegallery.
class FacegalleryViewComments extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	//  Display the view	 
	public function display($tpl = null)
	{
           
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors));
		}
        
		FacegalleryHelper::addSubmenu('comments');
        	$this->addToolbar();
                $this->sidebar = JHtmlSidebar::render();
                
		parent::display($tpl);
	}

	// Add the page title and toolbar.
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.'/helpers/facegallery.php';

        $user = JFactory::getUser();
		$state	= $this->get('State');
		$canDo	= FacegalleryHelper::getActions($state->get('filter.id'));

		JToolBarHelper::title(JText::_('COM_FACEGALLERY_MANAGER_COMMENTS'), 'comment.png');
               
       if ($canDo->get('core.edit.state'))
       {
       		if (isset($this->items[0]->state))
            {
			    JToolBarHelper::divider();
			    JToolBarHelper::custom('comments.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			    JToolBarHelper::custom('comments.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            }
            else if (isset($this->items[0]))
            {
                 //If this component does not use state then show a direct delete button as we can not trash
                 JToolBarHelper::deleteList('', 'comments.delete','JTOOLBAR_DELETE');
            }
       }
               	        
       //Show trash and delete for components that uses the state field
       if (isset($this->items[0]->state))
       {
		    if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
            {
			    JToolBarHelper::deleteList('', 'comments.delete','JTOOLBAR_EMPTY_TRASH');
			    JToolBarHelper::divider();
		    } 
        	else if ($canDo->get('core.edit.state'))
            {
			    JToolBarHelper::trash('comments.trash','JTOOLBAR_TRASH');
			    JToolBarHelper::divider();
		    }
       }

	    if ($canDo->get('core.admin'))
        {
			JToolBarHelper::preferences('com_facegallery');
	    }
        
        //Set sidebar action - New in 3.0
	    JHtmlSidebar::setAction('index.php?option=com_facegallery&view=comments');
        $this->extra_sidebar = '';

        JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_state',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions',array('archived'=>0)), 'value', 'text', $this->state->get('filter.state'), true)
		);
	}
    
	protected function getSortFields()
	{
		return array(		
		'a.state' => JText::_('JSTATUS'),
        'a.comment_name' => JText::_('COM_FACEGALLERY_FORM_LBL_COMMENT_COMMENT_NAME'),
        'a.comment_text' => JText::_('COM_FACEGALLERY_FORM_LBL_COMMENT_COMMENT_TEXT'),
        'a.created_on' => JText::_('COM_FACEGALLERY_FORM_LBL_COMMENT_CREATED_ON'),
		'a.id' => JText::_('JGRID_HEADING_ID'),
		);
	}    
}
