<?php
/**
 * Abstract Dashboard Widget
 *
 * @package   ZenCart\Admin\DashboardWidget
 * @copyright Copyright 2003-2015 Zen Cart Development Team
 * @license   http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version   GIT: $Id: $
 */

namespace ZenCart\Admin\DashboardWidget;

if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}

use base;

/**
 * AbstractWidget Class
 *
 * @package ZenCart\Admin\DashboardWidget
 */
abstract class AbstractWidget
{
  public function __construct($widgetKey, $widgetInfo = NULL)
  {
    $this->widgetInfo = $widgetInfo;
    $this->widgetKey = $widgetKey;
    $this->tplVars = array();
    $this->tplVars['content'] = array();
  }

  abstract public function prepareContent();

  public function getTemplateFile()
  {
    $tplFile = DIR_FS_ADMIN . DIR_WS_INCLUDES . 'template/dashboardWidgets/tpl' . base::camelize(strtolower($this->widgetKey), TRUE) . '.php';
    if (!file_exists($tplFile))
    {
      $tplFile = DIR_FS_ADMIN . DIR_WS_INCLUDES . 'template/dashboardWidgets/tplDefault.php';
    }
    return $tplFile;
  }

  public function getWidgetTitle()
  {
    $name = $this->widgetInfo['widget_name'];
    if (defined($name)) $name = constant($name);
    return $name;
  }

  public function getWidgetBaseId()
  {
    return strtolower(str_replace('_', '-', $this->widgetKey));
  }

  /**
   * Default form validation
   *
   * default form only contains settings for refresh, however does need to validate the securityToken
   * @return boolean
   */
  public function validateEditForm()
  {
    return TRUE;
  }

  public function getFormValidationErrors()
  {
    return $this->formValidationErrors;
  }

  public function executeEditForm()
  {
    if (isset($_POST['widget-refresh']))
    {
      $item = $_POST['id'];
      WidgetManager::setWidgetRefresh($_POST['widget-refresh'], $item, $_SESSION['admin_id']);
    }
  }

  public function setFormValidationErrors($errors)
  {
    $this->formValidationErrors = $errors;
  }

  public function getWidgetInfo()
  {
    return $this->widgetInfo;
  }

  public function getFormDefaults($item, $handler)
  {
      $result = WidgetManager::getWidgetRefresh($item, $_SESSION['admin_id']);
      $handler->setTplVars('widget-refresh', $result);
  }
}
