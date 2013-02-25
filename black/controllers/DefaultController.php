<?php

class DefaultController extends Controller
{
    /*
     * Adding a copypaster to the list of banned.
     *
     * Reason for the ban
     * $_POST['sea'] == 'uc' - Visitor has used hotkeys (Ctrl+C, Ctrl+X, Ctrl+U, Ctrl+A)
     * $_POST['sea'] == 'lt' - Visitor copied text
     */
    public function actionAdd()
    {
        $check = Yii::app()->request->getPost("client");
        if ($check != "ok")
            return true;

		$reason = 'unknown';
		$postReason = Yii::app()->request->getPost("sea");
		if ($postReason == 'uc')
			$reason = "CTRL key";
		if ($postReason == 'lt')
			$reason = "Copy large text";		

        $current_ip = $_SERVER['REMOTE_ADDR'];
        $current_user_agent = $_SERVER['HTTP_USER_AGENT'];

        $user = Banned::model()->findByAttributes(array(
            'ip'=>$current_ip,
        ));
        if ($user==null)
        {
            $user = new Banned();
            $user->ip = $current_ip;
            $user->user_agent = $current_user_agent;
			$user->reason = $reason;
            $user->save();
        }
    }
}