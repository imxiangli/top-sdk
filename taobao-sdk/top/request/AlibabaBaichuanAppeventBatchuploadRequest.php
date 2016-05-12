<?php
/**
 * TOP API: alibaba.baichuan.appevent.batchupload request
 * 
 * @author auto create
 * @since 1.0, 2016.05.10
 */
class AlibabaBaichuanAppeventBatchuploadRequest
{
	/** 
	 * app标识
	 **/
	private $appid;
	
	/** 
	 * 业务标识
	 **/
	private $bizid;
	
	/** 
	 * 行为参数
	 **/
	private $params;
	
	private $apiParas = array();
	
	public function setAppid($appid)
	{
		$this->appid = $appid;
		$this->apiParas["appid"] = $appid;
	}

	public function getAppid()
	{
		return $this->appid;
	}

	public function setBizid($bizid)
	{
		$this->bizid = $bizid;
		$this->apiParas["bizid"] = $bizid;
	}

	public function getBizid()
	{
		return $this->bizid;
	}

	public function setParams($params)
	{
		$this->params = $params;
		$this->apiParas["params"] = $params;
	}

	public function getParams()
	{
		return $this->params;
	}

	public function getApiMethodName()
	{
		return "alibaba.baichuan.appevent.batchupload";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->appid,"appid");
		RequestCheckUtil::checkNotNull($this->bizid,"bizid");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
