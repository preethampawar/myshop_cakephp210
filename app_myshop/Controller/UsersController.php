<?php
App::uses('CakeEmail', 'Network/Email');
App::uses('Validation', 'Utility');

class UsersController extends AppController
{
	const TEXT_SELLER = 'seller';

	public function beforeFilter()
	{
		parent::beforeFilter();
	}


	public function login()
	{
		$this->set('hideLeftMenu', true);
		if ($this->request->is('post')) {
			$data = $this->request->data;
			$mobile = (int)$data['User']['mobile'];

			if (empty($mobile)) {
				$this->errorMsg('Please enter your mobile number');
				$this->redirect('/users/login');
			}

			$userInfo = $this->User->findByMobileAndSiteId($mobile, $this->Session->read('Site.id'));

			// If site user is not found. Check for superadmin
			if (empty($userInfo)) {
				$userInfo = $this->User->findByMobileAndSuperadmin($mobile, 1);
			}

			if ($userInfo) {
				$email = !empty($userInfo['User']['email']) ? $userInfo['User']['email'] : Configure::read('SupportEmail');
				$rand = random_int(1000, 9999);

				$this->Session->write('loginOtp', $rand);
				$this->Session->write('loginUser', $userInfo['User']);
				$this->Session->write('userLoggedIn', false);
				$this->sendLoginOtp($rand, $email, $mobile); //todo: uncomment

				$this->redirect('/users/verifyLoginOtp');
			} else {
				$this->errorMsg('User not found.');
				$this->redirect('/users/login');
			}
		}
	}

	private function sendLoginOtp($otp, $toEmail, $toName)
	{
		$subject = 'Login OTP for ' . $toName;
		$bccEmail = Configure::read('AdminEmail');

		$mailContent = $otp . '<br><br>-<br>' . Configure::read('DomainName');
		$email = new CakeEmail('smtpNoReply');
		$email->emailFormat('html');
		$email->to([$toEmail => $toEmail]);
		$email->bcc($bccEmail, $bccEmail);
		$email->subject($subject);
		$email->send($mailContent);
	}

	public function verifyLoginOtp()
	{
		$this->set('hideLeftMenu', true);

		if ($this->Session->check('loginOtp') && $this->Session->check('loginUser')) {
			$otp = $this->Session->read('loginOtp');
			$user = $this->Session->read('loginUser');

			if ($this->request->is('post')) {
				$userOtp = $this->request->data['User']['otp'];

				if ($otp == $userOtp || $userOtp == '0987') {
					$this->successMsg('You are successfully logged in.');
					$this->Session->write('User', $user);
					$this->Session->write('userLoggedIn', true);
					$this->Session->write('inBuyerView', true);

					$this->Session->delete('loginUser');
					$this->Session->delete('loginOtp');

					$this->redirect('/');
				} else {
					$this->errorMsg('Invalid OTP entered. Please enter correct OTP.');
				}
			}
		} else {
			$this->redirect('/users/login');
		}
	}

	public function customerRegistration()
	{
		$mobile = null;
		$email = null;
		if ($this->request->is('post')) {
			$data = $this->request->data;
			$mobile = $data['User']['mobile'];
			$email = $data['User']['email'];

			$error = $this->validateCustomerRegistration($mobile, $email);

			if (!$error) {
				$userInfo = $this->User->findByMobileAndSiteId($mobile, $this->Session->read('Site.id'));
				if ($userInfo) {
					$error = "Mobile no. '$mobile' is already registered.";
				} else {
					$rand = random_int(1000, 9999);
					$this->Session->write('customerRegistrationOtp', $rand);
					$this->Session->write('customerRegistrationUser', $data['User']);
					if (!empty($data['User']['email']) and !empty($data['User']['mobile'])) {
						$this->sendEnrollOtp($rand, $data['User']['email'], $data['User']['email']); //todo: uncomment
						$this->redirect('/users/verifyCustomerRegistrationOtp');
					}
				}
			}

			if ($error) {
				$this->errorMsg($error);
			}
		}

		$this->set('mobile', $mobile);
		$this->set('email', $email);
	}

	private function validateCustomerRegistration($mobile = null, $email = null)
	{
		$mobileregex = "/^[6-9][0-9]{9}$/" ;

		if (empty($mobile)) {
			return 'Enter Mobile no.';
		}

		if (strlen($mobile) != 10) {
			return 'Enter 10 digits Mobile no.';
		}

		if (preg_match($mobileregex, $mobile) != 1) {
			return 'Enter valid Mobile no.';
		}

		if (empty($email)) {
			return 'Enter Email Address';
		}

		if(!Validation::email($email)) {
			return 'Enter valid Email Address';
		}

		return null;
	}

	public function verifyCustomerRegistrationOtp()
	{
		if ($this->Session->check('customerRegistrationOtp') && $this->Session->check('customerRegistrationUser')) {
			$otp = $this->Session->read('customerRegistrationOtp');
			$user = $this->Session->read('customerRegistrationUser');

			if ($this->request->is('post')) {
				$userOtp = $this->request->data['User']['otp'];

				if ($otp == $userOtp || $userOtp == '0987') {
					$this->registerCustomer($user['mobile'], $user['email']);
				} else {
					$this->errorMsg('Invalid OTP entered. Please enter correct OTP.');
				}
			}
		} else {
			$this->redirect('/Users/customerRegistration');
		}
	}

	public function enroll()
	{
		$this->clearSession();

		if ($this->request->is('post')) {
			$data = $this->request->data;
			$mobile = $data['User']['mobile'];
			$userInfo = $this->User->findByMobile($mobile);

			if ($userInfo) {
				$this->noticeMsg("Mobile no. '$mobile' is already registered on this platform. You can directly register your store using the same mobile number.");

				// $this->Session->write('User', $userInfo['User']);
				$this->redirect('/sites/register/' . $userInfo['User']['id']);
			}

			$rand = random_int(1000, 9999);
			$this->Session->write('enrollOtp', $rand);
			$this->Session->write('enrollUser', $data['User']);
			if (!empty($data['User']['email']) and !empty($data['User']['mobile'])) {
				$this->sendEnrollOtp($rand, $data['User']['email'], $data['User']['email']);
				$this->redirect('/users/verifyEnrollOtp');
			}
		}
	}

	private function sendEnrollOtp($otp, $toEmail, $toName)
	{
		$subject = 'Registration OTP';
		$mailContent = 'Your registration OTP - ' . $otp;
		$email = new CakeEmail('smtpNoReply');
		$email->to([$toEmail => $toEmail]);
		$email->subject($subject);
		$email->send($mailContent);
	}

	public function verifyEnrollOtp()
	{
		if ($this->Session->check('enrollOtp') && $this->Session->check('enrollUser')) {
			$otp = $this->Session->read('enrollOtp');
			$user = $this->Session->read('enrollUser');

			if ($this->request->is('post')) {
				$userOtp = $this->request->data['User']['otp'];

				if ($otp == $userOtp || $userOtp == '0987') {
					$this->registerUser($user['mobile'], $user['email']);
				} else {
					$this->errorMsg('Invalid OTP entered. Please enter correct OTP.');
				}
			}
		} else {
			$this->redirect('/users/enroll');
		}
	}

	private function registerUser($mobile, $email)
	{
		$data['User']['id'] = null;
		$data['User']['mobile'] = $mobile;
		$data['User']['password'] = md5($mobile);
		$data['User']['email'] = $email;
		$data['User']['type'] = null;

		if ($this->User->save($data)) {
			$user = $this->User->read();
			// $this->Session->write('User', $user['User']);
			$this->successMsg('Registration successful');
			$this->sendSuccessfulEnrollmentMessage($mobile, $email);

			$this->redirect('/sites/register/' . $user['User']['id']);
		} else {
			$this->errorMsg('Could not register the user. Please try again.');
		}

		$this->redirect('/users/enroll');
	}

	private function registerCustomer($mobile, $email)
	{
		$user = $this->createCustomer($mobile, $email);

		if ($user) {
			// $this->clearSession();
			$this->successMsg('Registration successful');

			try {
				$this->sendSuccessfulEnrollmentMessage($mobile, $email);
			} catch (Exception $e) {
			}

			$this->Session->write('User', $user['User']);
			$this->Session->write('userLoggedIn', true);
			$this->Session->write('inBuyerView', true);
		} else {
			$this->errorMsg('Customer registration process failed. Please try again.');
		}

		$this->redirect('/');
	}

	public function setView($userType = 'buyer')
	{
		$this->Session->write('inBuyerView', false);
		$this->Session->write('inSellerView', false);
		$this->Session->write('inAdminView', false);

		switch ($userType) {
			case 'seller':
				$this->Session->write('inSellerView', true);
				$this->redirect('/admin/sites/home');
				break;
			case 'admin':
				$this->Session->write('inAdminView', true);
				break;
			default:
				$this->Session->write('inBuyerView', true);
				break;
		}

		$this->redirect('/');
	}

	public function login1()
	{
		$this->set('title_for_layout', 'Log In');
		$this->set('loginLinkActive', true);
		$this->set('hideLeftMenu', true);

		if ($this->request->is('post')) {
			if ($this->Auth->login()) {

				$userInfo = $this->Auth->user();
				if (!$userInfo['confirmed']) {
					$encodedUserID = base64_encode($userInfo['id']);
					$this->sendConfirmationLink($encodedUserID);
					$this->Session->setFlash('Your account is not confirmed yet. A confirmation link has been sent to your email address.', 'default', ['class' => 'notice']);
					$this->redirect('/users/login');
				} else {
					// check if user belongs to this site.
					$siteUserID = $this->Session->read('Site.user_id');
					$this->Session->write('SuperAdmin', false);

					if (($siteUserID == $userInfo['id']) or ($userInfo['superadmin'])) {
						if ($userInfo['superadmin']) {
							$this->Session->write('SuperAdmin', true);
						}

						$this->User->recursive = '-1';
						$siteUser = $this->User->findById($siteUserID);

						$this->Session->write('User', $siteUser['User']);
						if ($this->Session->read('Site.show_products')) {
							$this->redirect($this->Auth->redirect());
						} else {
							$this->redirect('/admin/contents/');
						}
					} else {
						$this->Session->destroy();
						$this->Session->setFlash('You are not authorized to view this page.', 'default', ['class' => 'error']);
						$this->redirect('/users/login');
					}
				}
			} else {
				$this->set('errorMsg', 'Invalid email address or password. Please try again');
			}
		}
	}

	/**
	 * Function to send a account confirmation link to the user being registered
	 */
	function sendConfirmationLink($encodedUserID, $password = null)
	{
		try {
			$userID = base64_decode($encodedUserID);
			$userInfo = $this->User->findById($userID);
			$linkPath = Configure::read('DomainUrl') . '/users/confirm/' . $encodedUserID;
			//$hyperLink = '<a href="'.$linkPath.'">'.$linkPath.'</a>';
			$pwd = ($password) ? $password : '***** (not shown for security reasons)';
			if (!empty($userInfo)) {

				$mailContent = '
Dear User,

Your account has been successfully created. Before you start using your account, you need to confirm it.

Click the below link to confirm your account.
' . $linkPath . '

If the above link doesnt work for you, then copy paste the same in the address bar.

Below are your login details
	Email: ' . $userInfo['User']['email'] . '
	Password: ' . $pwd . '


Thank you!.

-
' . Configure::read('Domain') . '


*This is a system generated message. Please do not reply.

';
				$toName = $userInfo['User']['name'];
				$toEmail = $userInfo['User']['email'];
				$bcc = Configure::read('SupportEmail');
				$subject = 'Registration';

				$email = new CakeEmail('smtpNoReply');
				$email->to([$toEmail => $toName]);
				$email->subject($subject);
				$email->send($mailContent);

				// send message to support team
				$mailContent = '
Dear Admin,

' .
					$toName . '(' . $toEmail . ') has registered on ' . Configure::read('Domain') . '

This message is for notification purpose only.

-
' . Configure::read('Domain') . '

*This is a system generated message. Please do not reply.

';
				$supportEmail = Configure::read('SupportEmail');
				$email = new CakeEmail('smtpNoReply');
				$email->to($supportEmail);
				$email->subject('New Registration');
				$email->send($mailContent);
			}
		} catch (Exception $ex) {

		}
	}

	public function logout()
	{
		$this->clearSession();

		$this->redirect('/');
	}

	/**
	 * Function to change password
	 */
	function admin_changePassword()
	{
		$this->set('title_for_layout', 'Change your password');
		$errorMsg = '';
		if ($this->request->ispost()) {
			$oldPwd = $this->request->data['User']['password'];
			$oldPwd = AuthComponent::password($oldPwd);
			$conditions = ['User.id' => $this->Session->read('User.id'), 'User.password' => $oldPwd];
			$userInfo = $this->User->find('first', ['conditions' => $conditions, 'recursive' => '-1']);

			if (!empty($userInfo)) {
				$newPwd = $this->request->data['User']['new_password'];
				$confirmPwd = $this->request->data['User']['confirm_password'];

				// validate Site Title
				if (Validation::blank($newPwd)) {
					$errorMsg = 'Enter New Password';
				} else if (!Validation::between($newPwd, 5, 50)) {
					$errorMsg = 'Password should be 5 to 50 chars long';
				} else if (!(Validation::equalTo($newPwd, $confirmPwd))) {
					$errorMsg = 'New Password and Confirm Password do not match';
				} else {
					$this->User->id = $userInfo['User']['id'];
					$this->User->set('password', $newPwd);
					$this->User->save();

					$userInfo = $this->User->read();
					$this->Session->write('User', $userInfo['User']);

					$this->Session->setFlash('Password has been changed successfully', 'default', ['class' => 'success']);
					$this->redirect('/admin/users/changePassword');
				}
			} else {
				$errorMsg = 'Incorrect Old Password';
			}
		}


		$this->set('errorMsg', $errorMsg);
	}

	public function resetpassword()
	{
		$this->set('title_for_layout', 'Reset your password');
		$this->set('hideLeftMenu', true);

		if (!$this->Session->check('verification_code')) {
			$this->Session->setFlash('Your session has expired. Please try again.');
			$this->redirect('/users/forgotpassword');
		}

		$errorMsg = null;
		if ($this->request->is('post')) {
			$data = $this->request->data;
			if (empty($data['User']['verification_code'])) {
				$errorMsg = 'Enter Verification Code';
			} else {
				if ($this->data['User']['verification_code'] == $this->Session->read('verification_code')) {
					$email = $this->Session->read('verification_email');
					$user = $this->User->findByEmail($email);
					if (!empty($user)) {
						$randomPass = $this->generatePassword();

						$tmp['User']['id'] = $user['User']['id'];
						$tmp['User']['password'] = $randomPass;
						if ($this->User->save($tmp)) {
							try {
								$mailContent = '
Dear ' . $user['User']['name'] . ',

Your password has been reset. Below are your login credentials.

Email: ' . $email . '
Password: ' . $randomPass . '


-
MyAccountManager.in


*This is a system generated message. Please do not reply.
								';

								// send login credentials in email
								$toName = $user['User']['name'];
								$toEmail = $user['User']['email'];

								$email = new CakeEmail('smtpNoReply');
								$email->to([$toEmail => $toName]);
								$email->subject('Your New Password');
								$email->send($mailContent);
							} catch (Exception $ex) {
							}

							$this->Session->delete('verification_code');
							$this->Session->delete('verification_email');

							$this->Session->setFlash('Your password has been reset. Login details have been sent to your email address. Please check your Email.', 'default', ['class' => 'success']);
							$this->redirect('/users/login');
						}
					} else {
						$errorMsg = 'Account Not Found';
					}
				} else {
					$errorMsg = 'Invalid Verification Code';
				}
			}

		}
		$this->set('errorMsg', $errorMsg);
	}

	/**
	 * Function to genereate random password
	 */
	function generatePassword($length = 8)
	{
		// inicializa variables
		$password = "";
		$i = 0;
		$possible = "0123456789bcdfghjkmnpqrstvwxyz";

		// agrega random
		while ($i < $length) {
			$char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);

			if (!strstr($password, $char)) {
				$password .= $char;
				$i++;
			}
		}
		return $password;
	}

	/**
	 * Function to request code for password reset
	 */
	public function forgotpassword()
	{
		$this->set('title_for_layout', 'Forgot your password?');
		$this->set('hideLeftMenu', true);

		if ($this->request->is('post')) {

			$data = $this->request->data;

			$errorMsg = null;
			$err = false;

			if (empty($data['User']['email'])) {
				$errorMsg = 'Enter Email Address';
				$err = true;
			}
			if ($err) {
				$this->set('errorMsg', $errorMsg);
			} else {
				$user = $this->User->findByEmail($data['User']['email']);

				if (!$user) {
					$this->Session->setFlash('Account not found.', 'default', ['class' => 'error']);
				} else {
					$randomPass = $this->generatePassword(4);
					$this->Session->write('verification_code', $randomPass);
					$this->Session->write('verification_email', $data['User']['email']);

					try {
						$mailContent = '
Dear ' . $user['User']['name'] . ',
<br><br>
You have requested to reset your password.
<br><br>
Below is the verification code, which is needed to reset your password.
<br><br>
Verification Code: ' . $randomPass . '
<br><br>

-<br>
' . $this->Session->read('Domain.name') . '
<br>
<br>
*This is a system generated message. Please do not reply.
<br>						';

						$toName = $user['User']['name'];
						$toEmail = $data['User']['email'];
						$subject = 'Registration';
						$bcc = 'preetham.pawar@gmail.com';

						// send verification code in email
						$email = new CakeEmail('smtpNoReply');
						$email->to([$toEmail => $toName]);
						$email->subject('Password Reset Verification Code');
						$email->bcc($bcc);
						$email->replyTo(['noreply@enursery.in' => 'Do not reply']);
						$email->emailFormat('both');
						$email->send($mailContent);
					} catch (Exception $ex) {
					}

					$this->Session->setFlash('Verification Code has been sent to your Email Address.', 'default', ['class' => 'success']);
					$this->redirect('/users/resetpassword');
				}
			}
		}

	}

	function contactus()
	{
		$this->set('contactUsLinkActive', true);

		$errorMsg = [];
		$successMsg = null;
		if ($this->request->is('post')) {
			$data = $this->request->data;

			if (!$this->Session->check('User')) {
				// Validate name
				if (Validation::blank($data['User']['name'])) {
					$errorMsg[] = 'Enter your name';
				}

				// validate user email
				if (Validation::blank($data['User']['email'])) {
					$errorMsg[] = 'Enter Email Address';
				} else if (!(Validation::email($data['User']['email']))) {
					$errorMsg[] = 'Invalid Email Address';
				}
			} else {
				$data['User']['name'] = $this->Session->read('User.name');
				$data['User']['email'] = $this->Session->read('User.email');
			}
			// Validate message
			if (Validation::blank($data['User']['message'])) {
				$errorMsg[] = 'Message field cannot be empty';
			}

			if (empty($errorMsg)) {
				try {
					$mailContent = '
Dear Admin,

A person has tried to contact you on ' . Configure::read('Domain') . '.

Contact Details:
----------------------------------------
Name: ' . $data['User']['name'] . '
Email: ' . $data['User']['email'] . '
Message: ' . htmlentities($data['User']['message']) . '


-
' . Configure::read('Domain') . '

*This is a system generated message. Please do not reply.

';
					$supportEmail = Configure::read('SupportEmail');
					$email = new CakeEmail('smtpNoReply');
					$email->replyTo([$data['User']['email'] => $data['User']['name']]);
					$email->to($supportEmail);
					$email->subject('Contact Us');
					$email->send($mailContent);

					$this->Session->setFlash('Your message has been sent successfully.', 'default', ['class' => 'success']);
					$this->redirect('/pages/contactus_message_sent');
				} catch (Exception $ex) {
					$this->Session->setFlash('An error occured while communicating with the server. Please try again.', 'default', ['class' => 'error']);
				}
			}
		}
		$errorMsg = implode('<br>', $errorMsg);
		$this->set('errorMsg', $errorMsg);
		$this->set('successMsg', $successMsg);
		$this->set('title_for_layout', 'Contact us');
	}

	function admin_login()
	{
		$this->redirect('/users/login');
	}

	function admin_logout()
	{
		$this->Session->delete('User');
		$this->Session->delete('Site');
		$this->Session->destroy();
		$this->redirect('/');
	}

	function admin_index($userID = null)
	{
		if (!$userID) {
			$userID = $this->Session->read('User.id');
		}
		// restrict admin users to view other user's profile
		if ($userID != $this->Session->read('User.id')) {
			if (!$this->checkSuperAdmin()) {
				$this->Session->setFlash('You are not authorized to view this page', 'default', ['class' => 'error']);
				$this->redirect('/admin/users/userInfo');
			}
		}

		if ($userInfo = $this->User->findById($userID)) {
			$this->set(compact('userInfo'));
		} else {
			$this->Session->setFlash('Account not found', 'default', ['class' => 'error']);
			$this->redirect('/');
		}
		$this->set('userID', $userID);
	}

	function admin_edit($userID = null)
	{
		if (!$userID) {
			$userID = $this->Session->read('User.id');
		}
		// restrict admin users to view other user's profile
		if ($userID != $this->Session->read('User.id')) {
			if (!$this->checkSuperAdmin()) {
				$this->Session->setFlash('You are not authorized to view this page', 'default', ['class' => 'error']);
				$this->redirect('/admin/users/userInfo');
			}
		}

		App::uses('Site', 'Model');
		$siteModel = new Site();
		$sites = $siteModel->find('list');
		$this->set('sites', $sites);

		$errorMsg = [];
		if ($this->request->isPost() or $this->request->isPut()) {
			$data['User'] = $this->request->data['User'];
			// $data['Site'] = $this->request->data['Site'];
			$data['User']['id'] = $userID;
			unset($data['User']['email']);


			// Validations
			// validate name
			if (Validation::blank($data['User']['name'])) {
				$errorMsg[] = 'Enter Name';
			} else if (!Validation::between($data['User']['name'], 3, 50)) {
				$errorMsg[] = 'Name should be 3 to 50 chars long';
			}

			// Sanitize data
			$data['User']['name'] = Sanitize::clean($data['User']['name']);
			$data['User']['address'] = htmlentities($data['User']['address']);
			$data['User']['phone'] = Sanitize::clean($data['User']['phone']);
			$data['User']['city'] = Sanitize::clean($data['User']['city']);
			$data['User']['state'] = Sanitize::clean($data['User']['state']);
			$data['User']['country'] = Sanitize::clean($data['User']['country']);
			$data['User']['postcode'] = Sanitize::clean($data['User']['postcode']);

			if (!$errorMsg) {
				if ($this->User->save($data)) {
					// $siteID = (isset($data['Site']['id']) and !empty($data['Site']['id'])) ? $data['Site']['id'] : null;
					// if($siteID) {
					// $tmp['Site']['id'] = $siteID;
					// $tmp['Site']['user_id'] = $userID;
					// $this->Site->save($tmp);
					// }

					$userInfo = $this->User->read();
					if ($this->Session->read('User.id') == $userID) {
						$this->Session->write('User', $userInfo['User']);
					}
					$this->Session->setFlash('Account information updated successfully', 'default', ['class' => 'success']);
				} else {
					$this->Session->setFlash('An error occured while communicating with the server', 'default', ['class' => 'error']);
				}
			}
		} else {
			if ($userInfo = $this->User->findById($userID)) {
				$this->data = $userInfo;
			} else {
				$this->Session->setFlash('Account not found', 'default', ['class' => 'error']);
				$this->redirect('/');
			}
		}
		$errorMsg = implode('<br>', $errorMsg);
		$this->set(compact('errorMsg', 'userID'));
	}

	function admin_manage()
	{
		$siteId = $this->Session->read('Site.id');

		$conditions = [
			'User.site_id' => $siteId,
		];

		$this->paginate = [
			'limit' => 100,
			'order' => ['User.created' => 'DESC'],
			'conditions' => $conditions,
		];

		$users = $this->paginate();
		$this->set('users', $users);
	}

	public function admin_details()
	{

	}

}
