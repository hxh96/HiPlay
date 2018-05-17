<?php
/**
 * Created by PhpStorm.
 * User: hxh
 * Date: 2018/5/16
 * Time: 15:21
 */

namespace Common\Model;


use Think\Model;

class UserAttentionModel extends Model
{
    private $_db = '';

    public function __construct()
    {
        $this->_db = M('UserAttention');
    }


    /**
     * 根据条件获取所有
     * @param string $map 条件
     * @return mixed
     */
    public function getAll($map = '')
    {
        $arr = $this->_db->where($map)->order('id desc')->select();
        return $arr;
    }


    /**
     * 被关注用户ID关联用户表,根据条件获取所有
     * @param string $map 条件
     * @param string $limit 条数
     * @return mixed
     */
    public function getByAttentionUserIdJoinUser($map = '',$limit='')
    {
        $arr = $this->_db
            ->field([
                'hiwan_user_attention.id',
                'hiwan_user_attention.user_id',
                'hiwan_user_attention.attention_user_id',
                'hiwan_user_attention.status',
                'hiwan_user_attention.inputtime',
                'hiwan_user.user_name',
                'hiwan_user.sex',
                'hiwan_user.head_img',
                'hiwan_user.personality_sign'
            ])
            ->join('hiwan_user on hiwan_user_attention.attention_user_id = hiwan_user.id')
            ->where($map)
            ->order('id desc')
            ->limit($limit)
            ->select();
        return $arr;
    }


    /**
     * 关注用户ID关联用户表,根据条件获取所有
     * @param string $map 条件
     * @param string $limit 条数
     * @return mixed
     */
    public function getByUserIdJoinUser($map = '',$limit='')
    {
        $arr = $this->_db
            ->field([
                'hiwan_user_attention.id',
                'hiwan_user_attention.user_id',
                'hiwan_user_attention.attention_user_id',
                'hiwan_user_attention.status',
                'hiwan_user_attention.inputtime',
                'hiwan_user.user_name',
                'hiwan_user.sex',
                'hiwan_user.head_img',
                'hiwan_user.personality_sign'
            ])
            ->join('hiwan_user on hiwan_user_attention.user_id = hiwan_user.id')
            ->where($map)
            ->order('id desc')
            ->limit($limit)
            ->select();
        return $arr;
    }

    /**
     * 关联用户表  根据条件获取所有
     * @param string $map 条件
     * @return mixed
     */
    public function getAllJoinUser($map = '', $limit = '')
    {
        $arr = $this->_db
            ->field([
                'hiwan_user.user_name',
                'hiwan_user.phone',
                'hiwan_user_attention.user_id',
                'hiwan_user_attention.attention_user_id',
                'hiwan_user_attention.status',
                'hiwan_user_attention.inputtime',
            ])
            ->join('hiwan_user on hiwan_user_attention.user_id = hiwan_user.id')
            ->where($map)
            ->order('hiwan_user_attention.id desc')
            ->limit($limit)
            ->select();
        return $arr;
    }

    /**
     * 根据id获取一条
     * @param $id id
     * @return mixed
     */
    public function getById($id)
    {
        $arr = $this->_db->where(array('id'=>$id))->find();
        return $arr;
    }

    /**
     * 根据条件获取一条
     * @param string $map 条件
     * @return mixed
     */
    public function getOne($map = '')
    {
        $arr = $this->_db->where($map)->find();
        return $arr;
    }

    /**
     * 更新数据
     * @param $data 数据
     * @return bool
     */
    public function updateData($data)
    {
        $arr = $this->_db->save($data);
        return $arr;
    }

    /**
     * 添加数据
     * @param $data 数据
     * @return mixed
     */
    public function addData($data)
    {
        $arr = $this->_db->add($data);
        return $arr;
    }
}