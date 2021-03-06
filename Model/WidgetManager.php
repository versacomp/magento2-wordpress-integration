<?php
/*
 *
 */	
namespace FishPig\WordPress\Model;

/* Constructor Args */
use Magento\Framework\View\Layout;

/* Misc */
use FishPig\WordPress\Block\Sidebar\Widget\AbstractWidget;

class WidgetManager
{	
	/*
	 * @var array
	 */
	protected $widgets = [];

	/*
	 * @var Layout
	 */
	protected $layout;
	
	/*
	 *
	 * @param  ModuleManaher $moduleManaher
	 * @return void
	 */
	public function __construct(array $widgets, Layout $layout)
	{
		$this->widgets = $widgets;
		$this->layout  = $layout;
	}
	
	/*
	 *
	 * @param  string @widgetName
	 * @return string|false
	 */
	public function getWidget($widgetName)
	{
		$widgetIndex = preg_match("/([0-9]{1,})$/", $widgetName, $widgetIndexMatch) ? (int)$widgetIndexMatch[1] : 0;
		$widgetName  = rtrim(preg_replace("/[^a-z_-]/i", '', $widgetName), '-');
		
		if (!isset($this->widgets[$widgetName])) {
			return false;
		}

		$widgetBlock = $this->layout->createBlock($this->widgets[$widgetName])		
			->setWidgetType($widgetName)
			->setWidgetName($widgetName)
			->setWidgetIndex($widgetIndex);
		
		if ($widgetBlock instanceof AbstractWidget) {
			return $widgetBlock;
		}

		return false;
	}
}
