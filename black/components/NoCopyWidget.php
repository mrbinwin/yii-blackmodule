<?php

/**
 * This widget protects content from parsers and copypasters
 */

class NoCopyWidget extends CWidget
{
	protected $_reason = 'unknown';

    /*
     * Check for ip in the list of banned, cookies and User-Agent before displaying pages
     */
	public function init()
	{
        $current_ip = $_SERVER['REMOTE_ADDR'];
		$current_user_agent = $_SERVER['HTTP_USER_AGENT'];
		
        $user = Banned::model()->findByAttributes(array(
            'ip'=>$current_ip,
        ));

        if ($user == null)
        { 
			if ((isset(Yii::app()->request->cookies['blu']->value)) or !($this->checkUA($current_user_agent)))
			{
				if (isset(Yii::app()->request->cookies['blu']->value)) $this->_reason = 'cookie';
				$user = new Banned();
            	$user->ip = $current_ip;
            	$user->user_agent = $current_user_agent;
				$user->reason = $this->_reason;
            	$user->save();				
			}             
        }

        if ($user!=null)
        {
            $cookie = new CHttpCookie('blu', 1);
            $cookie->expire = time()+60*60*24*180;
            $cookie->domain = '.'.preg_replace("/^www\./","",parse_url(Yii::app()->request->getHostInfo(), PHP_URL_HOST));
            Yii::app()->request->cookies['blu'] = $cookie;

            sleep(15);
            Yii::app()->errorHandler->errorAction = null;
            throw new CHttpException(502,'Bad gateway');
        }
	}

	public function run()
	{
        Yii::app()->clientScript->registerScript('blackW','
            function blwg(event) {
                var event = event || window.event;
                if (event.preventDefault) { event.preventDefault(); }
                else { event.returnValue = false; }
                return false;
            };
            function blwu(event) {
                var code=event.keyCode ? event.keyCode : event.which ? event.which : null;
                if (event.ctrlKey){
                    if ((code == 117) || (code == 85))
                    {
                        $.post("'.Yii::app()->createUrl('black/default/add').'",{client:"ok",sea:"uc"});
                        return false;

                    };
                    if ((code == 97) || (code == 65)) return false;
                };
            };
            function blwc(event) {
                if (window.getSelection().toString().length > 200)
                {
                    $.post("'.Yii::app()->createUrl('black/default/add').'",{client:"ok",sea:"lt"});
                    return false;
                };
            };
            document.onkeydown = blwu;
            document.onkeypress = blwu;
            document.oncontextmenu = blwg;
            document.ondragstart = blwg;
            document.oncopy = blwc;
        ',CClientScript::POS_READY);
    }

    /*
     * Check User-Agent. You can modify this list according to your preferences.
     */
	protected function checkUA($current_user_agent)
	{
		$black_ua_arr = array(
			'acul',
			'aculinx',
			'adressendeutschland',
			'alexibot',
			'alligator',
			'allsubmitter',
			'almaden',
			'anarchie',
			'anonymous',
			'anonymouse',
			'ants',
			'aspseek',
			'asterias',
			'attach',
			'autoemailspider',
			'backdoorbot',
			'backstreet',
			'backweb',
			'badass',
			'bandit',
			'batchftp',
			'becomebot',
			'bitacle',
			'black.hole',
			'blackwidow',
			'bladder',
			'blogshares',
			'blowfish',
			'bmlauncher',
			'board bot',
			'buddy',
			'builtbottough',
			'bullseye',
			'bumblebee',
			'bunnyslippers',
			'burner',
			'caitoo',
			'cdefprs',
			'cegbfeieh',
			'cfnetwork',
			'cheesebot',
			'cherry',
			'chinaclaw',
			'cicc',
			'collect',
			'collector',
			'commander',
			'convera',
			'converamultimediacrawler',
			'copier',
			'copyrightcheck',
			'coral',
			'cosmos',
			'craftbot@yahoo.com',
			'crawl_application',
			'crescent',
			'c-spider',
			'curl',
			'custo',
			'da 4.0',
			'da 5.0',
			'da 5.3',
			'da_7.0',
			'demo',
			'demon',
			'devil',
			'dirtysexsearch',
			'disco',
			'dittospyder',
			'dloader',
			'dnloadmage',
			'down2web',
			'downes',
			'download',
			'downloader',
			'downloadit',
			'dragonfly',
			'dreampassport',
			'drip',
			'dsurf',
			'easydl',
			'eater',
			'eboard',
			'ecatch',
			'eclipt',
			'eirgrabber',
			'emailcollector',
			'emailsiphon',
			'emailwolf',
			'enterprise',
			'erocrawler',
			'express',
			'extractor',
			'eyenetie',
			'fdm_2',
			'filehound',
			'firefly',
			'flashget',
			'flashsite',
			'flipbrowser',
			'foobot',
			'fsurf',
			'fuck',
			'gaisbot',
			'gamespy_arcade',
			'geniebot',
			'getbot',
			'getright',
			'gets',
			'getsmart',
			'getweb',
			'gigabaz',
			'gigabot',
			'go!zilla',
			'go-ahead-got-it',
			'goforitbot',
			'gornker',
			'gotit',
			'grab',
			'grabber',
			'grabnet',
			'grafula',
			'greed',
			'grub-client',
			'harvest',
			'heritrix',
			'hloader',
			'hmview',
			'hoover',
			'hoowwwer',
			'htget',
			'httpdown',
			'httplib',
			'httrack',
			'humanlinks',
			'ia_archive',
			'ia_archiver',
			'ibrowse',
			'iccrawler',
			'ichiro',
			'ifox98',
			'igetter',
			'imds_monitor',
			'indy',
			'ineta',
			'infonavirobot',
			'interget',
			'internetlinkagent',
			'internetseer.com',
			'iphoto',
			'iria',
			'irlbot',
			'irvine',
			'jakarta',
			'java/1.1.4',
			'java/1.3.0',
			'java/1.3.1',
			'java/1.3.1_02',
			'java/1.4.1',
			'java/1.4.1_04',
			'java/1.4.2',
			'java/1.4.2_01',
			'java/1.4.2_02',
			'java/1.4.2_03',
			'java/1.4.2_04',
			'java/1.4.2_05',
			'java/1.4.2_06',
			'java/1.4.2_07',
			'java/1.5.0',
			'java/1.5.0_01',
			'java/1.5.0_02',
			'java/1.5.0_03',
			'java/1.5.0_04',
			'java/1.5.0_05',
			'java/1.5.0_06',
			'java/1.5.0_07',
			'java/1.5.0_08',
			'java/1.5.0_09',
			'java/1.5.0_10',
			'java/1.5.0_11',
			'java/1.6.0_01',
			'java/1.6.0_02',
			'java/1.6.0_03',
			'java/1.6.0-oem',
			'java/1.6.0-rc',
			'java_1.1',
			'jbh.*agent',
			'jennybot',
			'jetcar',
			'jeteye',
			'jeteyebot',
			'jobo',
			'joc web spider',
			'justview',
			'kapere',
			'keepoint',
			'kenjin.spider',
			'keyword.density',
			'lachesis',
			'larbin',
			'leech',
			'leechftp',
			'leechget',
			'lexibot',
			'lftp',
			'libweb',
			'libwww',
			'libwww-perl',
			'lightningdownload',
			'likse',
			'link.*sleuth',
			'linkextractorpro',
			'linkie',
			'linkscan',
			'linkwalker',
			'magnet',
			'mag-net',
			'mass',
			'mata.hari',
			'memo',
			'memoweb',
			'microsoft.url',
			'midown',
			'mihov_picture_downloader',
			'miixpc',
			'missigua_locator_1.9',
			'mister',
			'misterprivacy',
			'moget',
			'moozilla',
			'mozilla.*indy',
			'mozilla.*newt',
			'msfrontpage',
			'msproxy',
			'muncher',
			'myfamilybot',
			'mysmutsearch',
			'nameprotect',
			'nasa search',
			'naver',
			'naverbot',
			'naverrobot',
			'navroad',
			'nearsite',
			'netants',
			'netdrag',
			'netmechanic',
			'netresearchserver',
			'netspider',
			'netzip',
			'newt activex',
			'newt',
			'nextopia',
			'nicerspro',
			'nimblecrawler',
			'ninja',
			'nitro',
			'npbot',
			'nutch',
			'nutscrape',
			'octopus',
			'oegp',
			'offline',
			'omniexplorer',
			'openfind',
			'outfoxbot',
			'p3p',
			'pagegrabber',
			'pagmiedownload',
			'papa',
			'pavuk',
			'pcbrowser',
			'ping',
			'plantynet_webrobot',
			'playstarmusic',
			'pockey',
			'production',
			'prowebwalker',
			'psbot/0.1',
			'psycheclone',
			'pump',
			'pussycat',
			'puxarapido',
			'python',
			'python-urllib',
			'qrva',
			'quepasacreep',
			'queryn.metasearch',
			'quester',
			'realdownload',
			'reaper',
			'recorder',
			'redkernel',
			'reget',
			'relevantnoise',
			'repomonkey',
			'retriever',
			'sakura',
			'sauger',
			'sbider',
			'scooter',
			'searchexpress',
			'seekbot',
			'seeker',
			'siphon',
			'sitecheck.internetseer.com',
			'sitesnagger',
			'slurp',
			'slysearch',
			'smartdownload',
			'sna-',
			'snagger',
			'snake',
			'snap bot',
			'snarf',
			'snatcher',
			'snoopy',
			'spacebison',
			'spankbot',
			'spanner',
			'speeddownload',
			'sphere',
			'spider',
			'sproose',
			'squid-prefetch',
			'stamina',
			'stripper',
			'sucker',
			'superbot',
			'superhttp',
			'surfbot',
			'surfing',
			'suzuran',
			'sweeper',
			'szukacz',
			'takeout',
			'teleport',
			'telesoft',
			'thatrobotsite',
			'the.intraformant',
			'thenomad',
			'titan',
			'tmhtload',
			'tocrawl',
			'true_robot',
			'tunnelpro',
			'turingos',
			'turnitinbot',
			'tv33_mercator',
			'ucmore',
			'udmsearch',
			'urldispatcher',
			'urly.warning',
			'vacuum',
			'vampire',
			'voideye',
			'weazel',
			'web.*image.*collector',
			'webauto',
			'webbandit',
			'webcapture',
			'webcollage',
			'webcopier',
			'webdup',
			'webemailextrac',
			'webenhancer',
			'webexe',
			'webfetch',
			'webfilter',
			'webgo',
			'webhook',
			'webleacher',
			'webmasterworldforumbot',
			'webminer',
			'webmirror',
			'webpictures',
			'webpix',
			'webreaper',
			'webripper',
			'websauger',
			'website',
			'webster',
			'webstripper',
			'webvcr',
			'webwasher',
			'webwhacker',
			'webzip',
			'wells',
			'wget',
			'whacker',
			'widow',
			'winhttprequest',
			'wire/0.1',
			'wire/0.10',
			'wire/0.11',
			'wire/0.12',
			'wire/0.13',
			'wire/0.14',
			'wire/0.15',
			'wolf',
			'wonder',
			'www-collector-e',
			'wwwcopy',
			'wwwoffle',
			'xaldon',
			'xenu',
			'x-tractor',
			'yahoovideosearch',
			'yahooysmcm',
			'z-add',
			'zeus',
			'zyborg',
		);
		foreach ($black_ua_arr as $black_ua)
		{
			if (stripos($current_user_agent, $black_ua) !== false)
			{
				$this->_reason = 'user agent';
				return false;
			}						
		}
		
		$teleport_ua_arr = array(
			'Mozilla/5.0 (Windows; Windows NT) Firefox/3.6', //teleport
			'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT)',
			'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT)',
			'Mozilla/4.0 (compatible; MSIE 9.0; Windows NT)',
			'Mozilla/4.0 (compatible; MSIE 10.0; Windows NT)',
			'Internet Explorer', //offline explorer
		);
		if (in_array($current_user_agent, $teleport_ua_arr))
		{
			$this->_reason = 'user agent teleport';
			return false;
		}			
		
		return true;
	}

}