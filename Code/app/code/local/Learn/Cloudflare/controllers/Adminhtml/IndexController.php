<?php 
class Learn_Cloudflare_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{
	/*
	*
	*
	*/
    public function flushAction() {

		$_helper = Mage::helper('cloudflare');

        $json =  json_encode(
			array(
				'purge_everything' => true
			)
		);
		
		if($_helper->cloudflare_enable()) {
			$email_id 	= $_helper->cloudflare_email_id();
			$api_key 	= $_helper->cloudflare_api_key();
			$zone_id 	= $_helper->cloudflare_zone_id();
			$api_url	= "https://api.cloudflare.com/client/v4/zones/". $zone_id ."/purge_cache";
			
			if( $email_id && $api_key && $zone_id ) {			
				
				$result = Mage::getModel('cloudflare/api')->curlDeleteRequest($api_url, $json, $email_id, $api_key);
				
				if($result['success'] == 1) {
					$message = $this->__("cache refreshed successfully.");
					Mage::getSingleton('core/session')->addSuccess($message);
					return true;
				} else {
					$message = $this->__("Error - flushing the cache.");
					Mage::getSingleton('core/session')->addError($message);	
				}
			} else {
				$message = $this->__("Kindly check the Email-ID, API-Key and Zone ID.");
				Mage::getSingleton('core/session')->addNotice($message);	
			}
		} else {
			$message = $this->__("Kindly enable the module for cloudflare settings control.");
			Mage::getSingleton('core/session')->addNotice($message);
		}
		
		$this->_redirectReferer();
    }
}