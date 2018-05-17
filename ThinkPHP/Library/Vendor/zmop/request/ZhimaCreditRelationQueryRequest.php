<?php
/**
 * ZHIMA API: zhima.credit.relation.query request
 *
 * @author auto create
 * @since 1.0, 2018-04-11 14:24:18
 */
class ZhimaCreditRelationQueryRequest
{
	/** 
	 * 申请人证件号，中国大陆18或者15位合法身份号
	 **/
	private $certNo;
	
	/** 
	 * 芝麻平台服务商模式下的二级商户标识，如果是直连商户调用该接口，不需要设置
	 **/
	private $linkedMerchantId;
	
	/** 
	 * 申请人证件姓名，简体中文，长度不超过64，姓名中不含",","/u0001"，"|","&","^","\\"等特殊字符
	 **/
	private $name;
	
	/** 
	 * 产品码，标记商户接入的具体产品；直接使用每个接口入参中示例值即可
	 **/
	private $productCode;
	
	/** 
	 * 申请人与联系人之间关系类型，目前支持4类关系，family(亲友)、workmate(同事)、schoolmate(同学)、friend(朋友)
	 **/
	private $relation;
	
	/** 
	 * 联系人姓名，简体中文，长度不超过64，姓名中不含",","/u0001"，"|","&","^","\\"等特殊字符
	 **/
	private $relationName;
	
	/** 
	 * 手机号码。中国大陆合法手机号，长度11位，不含国家代码
	 **/
	private $relationPhone;
	
	/** 
	 * 商户请求的唯一标志，长度64位以内字符串，仅限字母数字下划线组合。该标识作为业务调用的唯一标识，商户要保证其业务唯一性，使用相同transaction_id的查询，芝麻在一段时间内（一般为1天）返回首次查询结果，超过有效期的查询即为无效并返回异常，有效期内的重复查询不重新计费
	 **/
	private $transactionId;

	private $apiParas = array();
	private $fileParas = array();
	private $apiVersion="1.0";
	private $scene;
	private $channel;
	private $platform;
	private $extParams;

	
	public function setCertNo($certNo)
	{
		$this->certNo = $certNo;
		$this->apiParas["cert_no"] = $certNo;
	}

	public function getCertNo()
	{
		return $this->certNo;
	}

	public function setLinkedMerchantId($linkedMerchantId)
	{
		$this->linkedMerchantId = $linkedMerchantId;
		$this->apiParas["linked_merchant_id"] = $linkedMerchantId;
	}

	public function getLinkedMerchantId()
	{
		return $this->linkedMerchantId;
	}

	public function setName($name)
	{
		$this->name = $name;
		$this->apiParas["name"] = $name;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setProductCode($productCode)
	{
		$this->productCode = $productCode;
		$this->apiParas["product_code"] = $productCode;
	}

	public function getProductCode()
	{
		return $this->productCode;
	}

	public function setRelation($relation)
	{
		$this->relation = $relation;
		$this->apiParas["relation"] = $relation;
	}

	public function getRelation()
	{
		return $this->relation;
	}

	public function setRelationName($relationName)
	{
		$this->relationName = $relationName;
		$this->apiParas["relation_name"] = $relationName;
	}

	public function getRelationName()
	{
		return $this->relationName;
	}

	public function setRelationPhone($relationPhone)
	{
		$this->relationPhone = $relationPhone;
		$this->apiParas["relation_phone"] = $relationPhone;
	}

	public function getRelationPhone()
	{
		return $this->relationPhone;
	}

	public function setTransactionId($transactionId)
	{
		$this->transactionId = $transactionId;
		$this->apiParas["transaction_id"] = $transactionId;
	}

	public function getTransactionId()
	{
		return $this->transactionId;
	}

	public function getApiMethodName()
	{
		return "zhima.credit.relation.query";
	}

	public function setScene($scene)
	{
		$this->scene=$scene;
	}

	public function getScene()
	{
		return $this->scene;
	}
	
	public function setChannel($channel)
	{
		$this->channel=$channel;
	}

	public function getChannel()
	{
		return $this->channel;
	}
	
	public function setPlatform($platform)
	{
		$this->platform=$platform;
	}

	public function getPlatform()
	{
		return $this->platform;
	}

	public function setExtParams($extParams)
	{
		$this->extParams=$extParams;
	}

	public function getExtParams()
	{
		return $this->extParams;
	}	

	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function getFileParas()
	{
		return $this->fileParas;
	}

	public function setApiVersion($apiVersion)
	{
		$this->apiVersion=$apiVersion;
	}

	public function getApiVersion()
	{
		return $this->apiVersion;
	}

}
