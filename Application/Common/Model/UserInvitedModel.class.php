<?php
/**
 * 用户应邀模型
 */

namespace Common\Model;


use Think\Model;

class UserInvitedModel extends Model
{
    private $_db = '';

    public function __construct(){
        $this->_db = M('UserInvited');
    }

    /**
     * 找到对应应邀者的应邀信息
     * @param $id 应邀者id
     * @param $order 排序
     * @return mixed
     */
    public function getByUserId($id,$order='')
    {
        $arr = $this->_db->where(array('user_id'=>$id))->order($order)->select();
        return $arr;
    }

    /**
     * 找到ID应邀信息
     * @param $id id
     * @return mixed
     */
    public function getById($id)
    {
        $arr = $this->_db->where(array('id'=>$id))->find();
        return $arr;
    }

    /**
     * 根据条件获取所有
     * @param $map 条件
     * @param $limit 条数
     * @return mixed
     */
    public function getAll($map='',$limit='')
    {
        $arr = $this->_db->where($map)->order('id desc')->limit($limit)->select();
        return $arr;
    }


    /**
     * 找到对应活动的应邀信息
     * @param $id 活动ID
     * @return mixed
     */
    public function getByActivityId($id)
    {
        $arr = $this->_db->where(array('activity_id'=>$id,'status'=>1))->select();
        return $arr;
    }


    /**
     * 找到对应应邀者的应邀信息
     * @param $map 条件
     * @param string $order 排序
     * @param string $limit 条数
     * @return mixed
     */
    public function getAllJoinActivityAndJoinUser($map='',$order='',$limit='')
    {
        $arr = $this->_db
            ->field(
                [
                    'hiwan_user_invited.id',
                    'hiwan_activity.id as activity_id',
                    'hiwan_activity.title',
                    'hiwan_activity.activity_cate_id',
                    'hiwan_activity.category_id',
                    'hiwan_activity.merchants_id',
                    'hiwan_activity.join_number',
                    'hiwan_activity.content',
                    'hiwan_activity.inputtime as activity_inputtime',
//                    'hiwan_activity.is_official',
                    'hiwan_activity.sort',
                    'hiwan_activity.status as activity_status',
                    'hiwan_activity.start_time as activity_start_time',
                    'hiwan_activity.activity_address',
                    'hiwan_activity.activity_longitude',
                    'hiwan_activity.activity_latitude',
                    'hiwan_activity.initiate_user_id as user_id',
                    'hiwan_user.user_name',
                    'hiwan_user.sex',
                    'hiwan_user.phone',
                    'hiwan_user.head_img',
                    'hiwan_user.is_official',
                    'hiwan_user_invited.status',
                    'hiwan_user_invited.inputtime',
                    'hiwan_activity_cate.activity_cate_name'
                ]
            )
            ->join('hiwan_activity on hiwan_user_invited.activity_id = hiwan_activity.id')
            ->join('hiwan_user on hiwan_activity.initiate_user_id = hiwan_user.id')
            ->join('hiwan_activity_cate on hiwan_activity.activity_cate_id = hiwan_activity_cate.id')
            ->where($map)->order($order)->limit($limit)->select();
        return $arr;
    }

    /**
     * 根据条件获取一条数据
     * @param $map 条件
     * @return mixed
     */
    public function getOne($map)
    {
        $arr = $this->_db->where($map)->find();
        return $arr;
    }

    /**
     * 删除应邀的机器人活动信息
     * @param $id 机器人发布活动ID
     * @return mixed
     */
    public function deleteRobot($id)
    {
        $arr = $this->_db->where(array('activity_id'=>$id))->delete();
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

    /**
     * 更新数据
     * @param $data 数据
     * @return mixed
     */
    public function updateData($data)
    {
        $arr = $this->_db->save($data);
        return $arr;
    }
}