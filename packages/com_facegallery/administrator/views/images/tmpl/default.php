<?php
/*
 *********************************************************************
 * @name            Face Gallery
 * @version         1.0: view/images/tmpl/default.php$
 * @since           Joomla 3.0
 * @package         apptha
 * @subpackage      com_facegallery
 * @author          Apptha - http://www.apptha.com
 * @copyright       Copyright (C) 2013 Powered by Apptha
 * @license 		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @creationDate    March 13 2013
 *********************************************************************
 */

// No direct access
defined('_JEXEC') or die("restricted access");

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('dropdown.init');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_facegallery/assets/css/facegallery.css');
$trashed='';
$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$canOrder = $user->authorise('core.edit.state', 'com_facegallery');
$saveOrder =  'a.ordering';
if ($saveOrder) {
    $saveOrderingUrl = 'index.php?option=com_facegallery&task=images.saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'imageList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
?>

<script type="text/javascript">
    Joomla.orderTable = function() {
        table = document.getElementById("sortTable");
        direction = document.getElementById("directionTable");
        order = table.options[table.selectedIndex].value;
        if (order != '<?php echo $listOrder; ?>') {
            dirn = 'asc';
        } else {
            dirn = direction.options[direction.selectedIndex].value;
        }
        Joomla.tableOrdering(order, dirn, '');
    }
</script>

<?php
//Joomla Component Creator code to allow adding non select list filters
if (!empty($this->extra_sidebar)) {
    $this->sidebar .= $this->extra_sidebar;
}
?>

<form action="<?php echo JRoute::_('index.php?option=com_facegallery&view=images'); ?>" method="post" name="adminForm" id="adminForm">
    <?php if (!empty($this->sidebar)): ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
    <?php else : ?>
       <div id="j-main-container">
    <?php endif; ?>  
             
          <div id="filter-bar" class="btn-toolbar">
                  <!-- Showing search options -->
                  <div class="filter-search btn-group pull-left">
                      <label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER'); ?></label>
                      <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_FACEGALLERY_IMAGE_SEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('JSEARCH_FILTER'); ?>" />
                  </div>

                  <div class="btn-group pull-left">
                       <button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
                       <button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
                  </div>

                  <div class="btn-group pull-right hidden-phone">
                       <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
                       <?php echo $this->pagination->getLimitBox(); ?>
                  </div>

                  <!-- Showing Sorting options -->
                  <div class="btn-group pull-right hidden-phone">
                        <label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></label>
                        <select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
                            <option value=""><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></option>
                            <option value="asc" <?php if ($listDirn == 'asc')
                            echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING'); ?></option>
                            <option value="desc" <?php if ($listDirn == 'desc')
                            echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING'); ?></option>
                        </select>
                  </div>

                  <div class="btn-group pull-right">
                        <label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY'); ?></label>
                        <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
                            <option value=""><?php echo JText::_('JGLOBAL_SORT_BY'); ?></option>
                            <?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
                        </select>
                  </div>
          </div>

          <!-- Showing titles in grid view -->
          <div class="clearfix"> </div>
               <table class="table table-striped" id="imageList">
                  <thead>
                      <tr>                       
                       <th width="1%" class="nowrap center hidden-phone">
                           <?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
                        </th>
                        <th width="1%" class="nowrap center">
                            <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                        </th>                       
                        <th width="1%" class="nowrap center">
                           <?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
                        </th>
                        <th class="left">
                           <?php echo JHtml::_('grid.sort', 'COM_FACEGALLERY_TITLE_IMAGE', 'a.image', $listDirn, $listOrder); ?>
                        </th>
                        <th width="18%" class="left">
                           <?php echo JHtml::_('grid.sort', 'COM_FACEGALLERY_FORM_LBL_IMAGE_IMAGE_NAME', 'a.image', $listDirn, $listOrder); ?>
                        </th>   
                        <th width="19%" class="left">
                           <?php echo JHtml::_('grid.sort', 'COM_FACEGALLERY_FORM_LBL_IMAGE_UPLOAD_OPTION', 'a.upload_option', $listDirn, $listOrder); ?>
                        </th>                    
                        <th width="21%" class="left">
                           <?php echo JHtml::_('grid.sort', 'COM_FACEGALLERY_FORM_LBL_ALBUM_ALBUM_NAME', 'b.album_name', $listDirn, $listOrder); ?>
                        </th>
                        <th width="1%" class="nowrap center">
                           <?php echo JHtml::_('grid.sort','COM_FACEGALLERY_ALBUMS_COVER_IMAGE', 'a.cover_image', $listDirn, $listOrder) ?>
                        </th>
                        <th width="1%" class="nowrap center">
                            <?php echo JHtml::_('grid.sort', 'JFEATURED', 'a.featured', $listDirn, $listOrder); ?>
						</th>	
						<th width="18%" class="left">
                            <?php echo JHtml::_('grid.sort', 'COM_FACEGALLERY_IMAGES_CREATED_ON', 'a.created_on', $listDirn, $listOrder); ?>
                        </th>		
                         <th width="1%" class="nowrap center">
                            <?php echo JHtml::_('grid.sort', 'COM_FACEGALLERY_IMAGES_ORDERING', 'a.ordering', $listDirn, $listOrder); ?>
                        </th>
                        <th width="10%" class="nowrap hidden-phone">
                            <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'a.language', $listDirn, $listOrder); ?>
						</th>                        
                        <th width="1%" class="nowrap center ">
                            <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                        </th>                        
                       </tr>
                 </thead>
                                                
                 <tfoot>
                    <?php // Pagination
                        if (isset($this->items[0])) 
                        {
                            $colspan = count(get_object_vars($this->items[0]));
                        }
                        else 
                        {
                            $colspan = 10;
                        }
                    ?>
                    <tr>
                        <td colspan="<?php echo $colspan ?>">
                            <?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
                 </tfoot>
                 <tbody>
                 <?php
                      foreach ($this->items as $i => $item) :
                           $item->max_ordering = 0;
                           $ordering = ($listOrder == 'a.ordering');
                           $canCreate = $user->authorise('core.create', 'com_facegallery'.$item->id);
                           $canEdit = $user->authorise('core.edit', 'com_facegallery'.$item->id);
                           $canCheckin = $user->authorise('core.manage', 'com_facegallery'.$item->id)|| $item->checked_out == $userId || $item->checked_out == 0;
                           $canChange = $user->authorise('core.edit.state', 'com_facegallery'.$item->id) && $canCheckin;

                 ?>
                 <tr class="row<?php echo $i % 2; ?>">
                    <?php if (isset($this->items[0]->ordering)): ?>
                         <tr>
	                         <td class="order nowrap center hidden-phone">
	                             <?php
	                                if ($canChange) :
	                                    $disableClassName = '';
	                                    $disabledLabel = '';
	                                    if (!$saveOrder) :
	                                        $disabledLabel = JText::_('JORDERINGDISABLED');
	                                        $disableClassName = 'inactive tip-top';
	                                        endif;
	                             ?>
	                                    <span class="sortable-handler hasTooltip <?php echo $disableClassName ?>" title="<?php echo $disabledLabel ?>">
	                                            <i class="icon-menu"></i>
	                                    </span>
	                                    <input type="text" style="display:none" name="order[]" size="5"
	                                           value="<?php echo $item->ordering; ?>" class="width-20 text-area-order " />
	                             <?php else : ?>
	                                    <span class="sortable-handler inactive" >
	                                          <i class="icon-menu"></i>
	                                    </span>
	                             <?php endif; ?>
	                         </td>
                    			<?php endif; ?>

	                    <?php // Display image information in grid view ?>
	                    <td class="nowrap center">
	                            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
	                    </td>                       
	
	                    <?php if (isset($this->items[0]->state)): ?>
	                    <td class="nowrap center">
	                            <?php echo JHtml::_('jgrid.published', $item->state, $i, 'images.', $canChange, 'cb'); ?>
	                    </td>
	                    <?php endif; ?>
	
	                    <?php
	                            jimport('joomla.filter.output');
	                            $imagepath = JURI::base() . "components/com_facegallery/images";
	                            $imagelogopath = str_replace('administrator/','', $imagepath);
	
	                            $j = 0;
	                            for ($i = 0, $n = count($item); $i < $n; $i++) {
	                                //$row = &$item[$i];
	                                $link = JRoute::_('index.php?option=com_facegallery&task=image.edit&id=' . (int)$item->id);
	                                $link1 = JRoute::_('index.php?option=com_facegallery&view=images&cid[]=' . (int)$item->id . '&albumid=' . $item->albumid . '&set=1');
	                                $link0 = JRoute::_('index.php?option=com_facegallery&view=images&cid[]=' . (int)$item->id . '&albumid=' . $item->albumid . '&set=0');
	                                $link3 = JRoute::_('index.php?option=com_facegallery&view=images&cid[]=' . (int)$item->id . '&fset=1');
	                                $link2 = JRoute::_('index.php?option=com_facegallery&view=images&cid[]=' . (int)$item->id . '&fset=0');
	
	
	                    ?>
	                    
	                    <td class="nowrap has-context">
	                    	<div class="pull left">
	                        	<?php if ($canEdit) : ?>
		                            	<a href="<?php echo $link;?> ">
		                                <img height="50" width="50" src="<?php echo JURI::root()."images/facegallery/thumb_image/".$item->thumb_image; ?>" /></a>
	                            <?php else : ?>
										<img height="50" width="50" src="<?php echo JURI::root()."images/facegallery/thumb_image/".$item->thumb_image; ?>" /></a>
	                            <?php endif; ?>
	                        </div>
	                    </td>
	
	                    <td class="has-context" width="25%">
	                    	<div class="pull-left" style="width: 145px;">
	                        	<?php if ($canEdit) : ?>
	                            		<a href=" <?php echo $link; ?> ">
	                                	<?php echo $this->escape($item->image); ?>
	                                    </a>
	                                    <?php else : ?>
	                                        <?php echo $this->escape($item->image); ?>
	                                    <?php endif; ?>
	                        </div> 	
	                        <div class="pull-left">
	                        	<?php
	                            	// Create dropdown items
									JHtml::_('dropdown.edit', $item->id, 'image.');
									JHtml::_('dropdown.divider');
									if ($item->state) :
										JHtml::_('dropdown.unpublish', 'cb' . $i, 'images.');
									else :
										JHtml::_('dropdown.publish', 'cb' . $i, 'images.');
									endif;
	
									JHtml::_('dropdown.divider');
									if ($trashed) :
										JHtml::_('dropdown.untrash', 'cb' . $i, 'images.');
									else :
	                                	JHtml::_('dropdown.trash', 'cb' . $i, 'images.');
	                        		endif;
	
									// render dropdown list
									echo JHtml::_('dropdown.render');
	                            ?>
							</div>
	                    </td>
	                    
	                    <?php if (isset($this->items[0]->id)): ?>
	                    <td class="nowrap center">
	                    	<?php if($item->upload_option != 'File')
	                        	  {	
	                              	echo $item->upload_option;
	                              } 
	                        ?>
	                    </td>
	                    <?php endif; ?>
	                            
	                    <?php if (isset($this->items[0]->id)): ?>
	                    <td class='left'>
	                    	<?php echo $item->album_name; ?>
	                    </td>
	                    <?php endif; ?>
	
	                    <td class="nowrap center">
	                    <?php if ($item->cover_image) { ?>
	                    	<a  href="<?php echo $link0; ?>"
	                            title="<?php echo JText::_('Unset default Album Image'); ?>">
	                        	<img alt="cover image" src="<?php echo  JURI::base() . "components/com_facegallery/assets/images/star-icon.png" ?>" />
	                        </a>
						    <?php } else { ?>
	                     	<a  href="<?php echo $link1; ?>"
	                            title="<?php echo JText::_('Set as default Album Image'); ?>">
	                        	<img alt="cover image" src="<?php echo  JURI::base() . "components/com_facegallery/assets/images/star-empty-icon.png" ?>" />
	                        </a>
	                    <?php } ?>
	                    </td>
	
	                    <td class="nowrap center">
	                    <?php if ($item->featured) { ?>
	                    	<a  href="<?php echo $link2; ?>"
	                            title="<?php echo JText::_('Unset Featured'); ?>">
	                            <img alt="featured" src="<?php echo  JURI::base() . "components/com_facegallery/assets/images/star-icon.png" ?>" />
	                        </a>
	                        <?php } else { ?>
	                        <a  href="<?php echo $link3; ?>"
	                            title="<?php echo JText::_('Set as Featured'); ?>">
	                            <img alt="featured" src="<?php echo  JURI::base() . "components/com_facegallery/assets/images/star-empty-icon.png" ?>" />
	                        </a>
	                    <?php } ?>
	                   </td>
	                           
	                           
	                   <?php if (isset($this->items[0]->id)): ?>
	                   <td class="left">
	                   		<?php $createdOn = date('F d, Y ', strtotime($item->created_on)); 
	                        	   echo $createdOn; ?>
	                   </td>
	                   <?php endif; ?>
	
	                   <?php if (isset($this->items[0]->id)): ?>
	                   <td class="nowrap center">
	                   		<?php echo $item->ordering; ?>
	                   </td>
	                   <?php endif; ?>
	                           
	                   <?php if (isset($this->items[0]->id)): ?>
	                   <td class="small hidden-phone">
	                        <?php if ($item->language == '*'):?>
	                        	<?php echo JText::alt('JALL', 'language'); ?>
	                        <?php else:?>
	                        	<?php echo $item->language ? $this->escape($item->language) : JText::_('JUNDEFINED'); ?>
	                        <?php endif;?>
	                   </td>
	                   <?php endif; ?>
	
	                   <?php if (isset($this->items[0]->id)): ?>
	                   <td class="nowrap center">
	                   		<?php echo (int) $item->id; ?>
	                   </td>
	                   <?php endif; ?>
                 	</tr>
                 <?php $j++;
              }?>
              <?php endforeach; ?>
              </tbody>
            </table>
            <input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
            <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
            <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
</form>        
