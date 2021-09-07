<?php
App::uses('Component', 'Controller');
class SmsComponent extends Component {
    var $components = array('Session', 'Auth');

	public function sendOtp($toPhone, $otp)
	{
		$smsNotificationsEnabled = (bool)$this->Session->read('Site.sms_notifications');
		$smsProviderDetails = $this->Session->read('Site.sms_provider_details');

		if ($smsNotificationsEnabled === true) {
			$smsProviderDetails = json_decode($smsProviderDetails, true);
		}

		if (isset($smsProviderDetails['2Factor']) && !empty('2Factor')) {
			return $this->send2FactorOtp($smsProviderDetails['2Factor'], $toPhone, $otp);
		}

		return false;
	}

	private function send2FactorOtp($provider, $toPhone, $otp)
	{
		try {
			$apiKey = $provider['apiKey'];
			$templateName = $provider['otpTemplateName'];
			$otpUrl = $provider['otpUrl'];
			$toPhone = (int) $toPhone;
			$otp = (string)htmlentities($otp);

			$url = str_replace('{api_key}', $apiKey, $otpUrl);
			$url = str_replace('{phone_number}', $toPhone, $url);
			$url = str_replace('{otp}', $otp, $url);
			$url = str_replace('{template_name}', $templateName, $url);

			file_get_contents($url);
			return true;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
}
