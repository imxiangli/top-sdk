<?php
/**
 * 该程序仅在Yii2下可用
 * Created by PhpStorm.
 * User: lixiang
 * Date: 16/5/12
 * Time: 10:59
 */
namespace topsdk;

use yii\base\Component;
use yii\log\Logger;

class TopSdkYii2 extends Component
{
	public $appkey;
	public $secret;
	public $env = 't';
	public $gatewayUrl = 'http://gw.api.taobao.com/router/rest';

	private function getClient()
	{
		$c = new \TopClient;
		$c->appkey = $this->appkey;
		$c->format = 'json';
		$c->secretKey = $this->secret;
		$c->gatewayUrl = $this->gatewayUrl;
		return $c;
	}

	/**
	 * @param string $im_user_id im用户id
	 * @param string $im_password im密码
	 * @param string $name 名字
	 * @param string $nick 昵称
	 * @param string $gender 性别 M: 男, F：女
	 * @param array $other 参见 http://open.taobao.com/doc2/apiDetail.htm?apiId=24164
	 * @return boolean
	 */
	public function addImUser($im_user_id, $im_password, $name, $nick = null, $gender = null, $other = array())
	{
		if(!is_array($other)) $other = array();
		$req = new \OpenimUsersAddRequest;
		$userInfo = new \Userinfos;
		$userInfo->nick = $nick ? $nick : $name;
		$userInfo->icon_url = isset($other['icon_url']) ? $other['icon_url'] : null;
		$userInfo->email = isset($other['email']) ? $other['email'] : null;
		$userInfo->mobile = isset($other['mobile']) ? $other['mobile'] : null;
		$userInfo->taobaoid = isset($other['taobaoid']) ? $other['taobaoid'] : null;
		$userInfo->userid = $im_user_id;
		$userInfo->password = $im_password;
		$userInfo->remark = isset($other['remark']) ? $other['remark'] : null;
		$userInfo->extra = isset($other['extra']) ? json_encode($other['extra']) : '{}';
		$userInfo->career = isset($other['career']) ? $other['career'] : null;
		$userInfo->vip = isset($other['vip']) ? json_encode($other['vip']) : '{}';
		$userInfo->address = isset($other['address']) ? $other['address'] : null;
		$userInfo->name = $name;
		$userInfo->age = isset($other['age']) ? $other['age'] : null;
		$userInfo->gender = in_array($gender, array('M', 'F')) ? $gender : null;
		$userInfo->wechat = isset($other['wechat']) ? $other['wechat'] : null;
		$userInfo->qq = isset($other['qq']) ? $other['qq'] : null;
		$userInfo->weibo = isset($other['weibo']) ? $other['weibo'] : null;
		$req->setUserinfos(json_encode($userInfo));
		$client = $this->getClient();
		$resp = $client->execute($req);
		return true;
	}

	public function getImUsers($user_ids)
	{
		$client = $this->getClient();
		$req = new \OpenimUsersGetRequest;
		$req->setUserids($user_ids);
		$resp = $client->execute($req);
		return $resp;
	}

	public function delImUser($user_ids)
	{
		$client = $this->getClient();
		$req = new \OpenimUsersDeleteRequest;
		$req->setUserids($user_ids);
		$resp = $client->execute($req);
		return $resp;
	}

	public function notifyToAll($content, $title, $extras = null)
	{
		try {
			$client = $this->getClient();
			$req = new \CloudpushPushRequest;
			$req->setTarget("all");
			$req->setTargetValue("all");
			//$req->setAndroidActivity("/store/...");
			$req->setAndroidExtParameters(json_encode($extras));
			//$req->setAndroidMusic("default");
			//$req->setAndroidOpenType("1");
			//$req->setAndroidOpenUrl("http://www.taobao.com");
			//$req->setAntiHarassDuration("13");
			//$req->setAntiHarassStartTime("1");
			//$req->setBatchNumber("0001");
			$req->setBody($content);
			$req->setDeviceType("3");
			$req->setIosBadge("1");
			$iosExtras = $extras;
			if($this->env == 't')
			{
				$iosExtras['_ENV_'] = 'DEV'; // {"_ENV_": "DEV"} // 测试环境
			}
			$req->setIosExtParameters(json_encode($iosExtras));
			//$req->setIosMusic("default");
			$req->setRemind("true");
			$req->setStoreOffline("true");
			$req->setSummery($content);
			$req->setTimeout("72");
			$req->setTitle($title);
			$req->setType("1");
			$client->execute($req);
		} catch (\Exception $e) {
			$this->errorLog($e->getMessage().' '.$e->getTraceAsString());
		}
	}

	public function notifyToTags($tags, $content, $title, $extras = null)
	{
		try {
			$tag_ids = array();
			foreach ($tags as $key => $id) {
				$tag_ids[$key] = $this->env . $id;
			}
			if (empty($tag_ids)) {
				return;
			}
			$tagsString = implode(',', $tag_ids);

			$client = $this->getClient();
			$req = new \CloudpushPushRequest;
			$req->setTarget("tag");
			$req->setTargetValue($tagsString);
			//$req->setAndroidActivity("/store/...");
			$req->setAndroidExtParameters(json_encode($extras));
			//$req->setAndroidMusic("default");
			//$req->setAndroidOpenType("1");
			//$req->setAndroidOpenUrl("http://www.taobao.com");
			//$req->setAntiHarassDuration("13");
			//$req->setAntiHarassStartTime("1");
			//$req->setBatchNumber("0001");
			$req->setBody($content);
			$req->setDeviceType("3");
			$req->setIosBadge("1");
			$iosExtras = $extras;
			if($this->env == 't')
			{
				$iosExtras['_ENV_'] = 'DEV'; // {"_ENV_": "DEV"} // 测试环境
			}
			$req->setIosExtParameters(json_encode($iosExtras));
			//$req->setIosMusic("default");
			$req->setRemind("true");
			$req->setStoreOffline("true");
			$req->setSummery($content);
			$req->setTimeout("72");
			$req->setTitle($title);
			$req->setType("1");
			$client->execute($req);
		} catch (\Exception $e) {
			$this->errorLog($e->getMessage().' '.$e->getTraceAsString());
		}
	}

	public function notifyToOne($target_ids, $content, $title, $extras = null)
	{
		try {
			$accounts = array();
			foreach ($target_ids as $key => $id) {
				$accounts[$key] = $this->env . $id;
			}

			if (empty($accounts)) {
				return;
			}
			$accountsString = implode(',', $accounts);

			$client = $this->getClient();
			$req = new \CloudpushPushRequest;
			$req->setTarget("account");
			$req->setTargetValue($accountsString);
			//$req->setAndroidActivity("/store/...");
			$req->setAndroidExtParameters(json_encode($extras));
			//$req->setAndroidMusic("default");
			//$req->setAndroidOpenType("1");
			//$req->setAndroidOpenUrl("http://www.taobao.com");
			//$req->setAntiHarassDuration("13");
			//$req->setAntiHarassStartTime("1");
			//$req->setBatchNumber("0001");
			$req->setBody($content);
			$req->setDeviceType("3");
			$req->setIosBadge("1");
			$iosExtras = $extras;
			if($this->env == 't')
			{
				$iosExtras['_ENV_'] = 'DEV'; // {"_ENV_": "DEV"} // 测试环境
			}
			$req->setIosExtParameters(json_encode($iosExtras));
			//$req->setIosMusic("default");
			$req->setRemind("true");
			$req->setStoreOffline("true");
			$req->setSummery($content);
			$req->setTimeout("72");
			$req->setTitle($title);
			$req->setType("1");
			$rs = $client->execute($req);
			//Yii::log(serialize($rs), CLogger::LEVEL_ERROR);
		} catch (\Exception $e) {
			$this->errorLog($e->getMessage().' '.$e->getTraceAsString());
		}
	}

	private function errorLog($log)
	{
		\Yii::getLogger()->log($log, Logger::LEVEL_ERROR);
	}
}