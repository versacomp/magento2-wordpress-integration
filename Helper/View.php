<?php
/**
 * @category    Fishpig
 * @package     Fishpig_Wordpress
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

namespace FishPig\WordPress\Helper;

class View extends \Magento\Framework\App\Helper\AbstractHelper
{
	protected $_layout = null;
	protected $_config = null;
	protected $_request = null;
	
	public function __construct(
		\Magento\Framework\App\Helper\Context $context,
		\FishPig\WordPress\Model\Config $config,
		\Magento\Framework\View\Layout $layout,
    	\Magento\Framework\App\Request\Http $request
	)
	{
		parent::__construct($context);
		
		$this->_config = $config;
		$this->_layout = $layout;
		$this->_request = $request;
	}
	
	public function getRequest()
	{
		return $this->_request;
	}

	public function applyPageConfigData($pageConfig, $entity)
	{
        $pageConfig->getTitle()->set($entity->getPageTitle());
        $pageConfig->setDescription($entity->getMetaDescription());	
        $pageConfig->setKeywords($entity->getMetaKeywords());

        $pageMainTitle = $this->_layout->getBlock('page.main.title');
        
        if ($pageMainTitle) {
            $pageMainTitle->setPageTitle($entity->getName());
        }
        
		if ($entity->getCanonicalUrl()) {
			$pageConfig->addRemotePageAsset($entity->getCanonicalUrl(), 'canonical', ['attributes' => ['rel' => 'canonical']]);
		}

        return $this;
	}
	
	public function canDiscourageSearchEngines()
	{
		return (int)$this->_config->getOption('blog_public') === 0;
	}
	
	public function getBlogName()
	{
		return $this->_config->getOption('blogname');
	}
	
	public function getBlogDescription()
	{
		return $this->_config->getOption('blogdescription');
	}
	
	/**
	  * Formats a Wordpress date string
	  *
	  */
	public function formatDate($date, $format = null, $f = false)
	{
		if ($format == null) {
			$format = $this->getDefaultDateFormat();
		}
		
		/**
		 * This allows you to translate month names rather than whole date strings
		 * eg. "March","Mars"
		 *
		 */
		$len = strlen($format);
		$out = '';
		
		for( $i = 0; $i < $len; $i++) {	
			$out .= __(date($format[$i], strtotime($date)));
#			$out .= __(Mage::getModel('core/date')->date($format[$i], strtotime($date)));
		}
		
		return $out;
	}
	
	/**
	  * Formats a Wordpress date string
	  *
	  */
	public function formatTime($time, $format = null)
	{
		if ($format == null) {
			$format = $this->getDefaultTimeFormat();
		}
		
		return $this->formatDate($time, $format);
	}
	
	/**
	 * Split a date by spaces and translate
	 *
	 * @param string $date
	 * @param string $splitter = ' '
	 * @return string
	 */
	public function translateDate($date, $splitter = ' ')
	{
		$dates = explode($splitter, $date);
		
		foreach($dates as $it => $part) {
			$dates[$it] = $this->__($part);
		}
		
		return implode($splitter, $dates);
	}
	
	/**
	  * Return the default date formatting
	  *
	  */
	public function getDefaultDateFormat()
	{
		return 'F jS, Y';
		return $this->getWpOption('date_format', 'F jS, Y');
	}
	
	/**
	  * Return the default time formatting
	  *
	  */
	public function getDefaultTimeFormat()
	{
		return 'g:ia';
		return $this->getWpOption('time_format', 'g:ia');
	}
}
