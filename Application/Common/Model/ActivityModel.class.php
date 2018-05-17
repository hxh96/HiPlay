<?php
/**
 * 活动模型
 */

namespace Common\Model;


use Think\Model;

class ActivityModel extends Model
{
    private $_db = '';

    public function __construct(){
        $this->_db = M('activity');
    }

    /**
     * 找到对应发起者的活动信息
     * @param $id 发起者id
     * @return mixed
     */
    public function getByInitiateUserId($id)
    {
        $arr = $this->_db->where(array('initiate_user_id'=>$id))->select();
        return $arr;
    }


    /**
     * 根据ID找到一条活动
     * @param $id id
     * @return mixed
     */
    public function getById($id)
    {
        $arr = $this->_db->where(array('id'=>$id))->find();
        return $arr;
    }

    /**
     * 根据分类ID查找数据
     * @param $id 分类ID
     * @return mixed
     */
    public function getByCategoryId($id)
    {
        $map['category_id'] = $id;
        $map['status'] = array('neq',2);
        $arr = $this->_db->where($map)->select();
        return $arr;
    }


    /**
     * 根据条件获取所有
     * @param $map 条件表达式
     * @param $limit 条数
     * @return mixed
     */
    public function getAll($map='',$order='sort desc,inputtime desc,id desc',$limit='')
    {
        $arr = $this->_db->where($map)->order($order)->limit($limit)->select();
        return $arr;
    }


    /**
     * 根据条件获取所有
     * @param $map 条件表达式
     * @param $limit 条数
     * @return mixed
     */
    public function getAllJoinUser($map='',$order='hiwan_user.is_official desc,hiwan_activity.sort desc,hiwan_user.is_robot asc,hiwan_activity.start_time asc,hiwan_activity.inputtime desc',$limit='')
    {
        $arr = $this->_db
            ->field(
                [
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
                    'hiwan_activity.initiate_user_id',
                    'hiwan_user.user_name',
                    'hiwan_user.sex',
                    'hiwan_user.phone',
                    'hiwan_user.head_img',
                    'hiwan_user.is_official'
                ]
            )
            ->join('hiwan_user on hiwan_activity.initiate_user_id = hiwan_user.id')
            ->where($map)
            ->order($order)
            ->limit($limit)
//            ->fetchSql(true)
            ->select();
        return $arr;
    }

    /**
     * 根据条件获取一条
     * @param $map 条件表达式
     * @return mixed
     */
    public function getOneJoinUserById($map='')
    {
//        $map['hiwan_activity.status']  = array(array('neq',2),array('neq',3),'and');
//        $map['hiwan_user.status'] = array('eq',1);
        $arr = $this->_db
            ->field(
                [
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
                    'hiwan_activity.initiate_user_id',
                    'hiwan_user.user_name',
                    'hiwan_user.sex',
                    'hiwan_user.phone',
                    'hiwan_user.head_img',
                    'hiwan_user.is_official'
                ]
            )
            ->join('hiwan_user on hiwan_activity.initiate_user_id = hiwan_user.id')
            ->where($map)
//            ->fetchSql(true)
            ->find();
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
     * @return bool
     */
    public function addData($data)
    {
        $arr = $this->_db->add($data);
        return $arr;
    }


    /**
     * 删除机器人发布的活动
     * @param $id 机器人ID
     * @return mixed
     */
    public function deleteRobot($id)
    {
        $arr = $this->_db->where(array('initiate_user_id'=>$id))->delete();
        return $arr;
    }

    /**
     * 删除数据
     * @param $id id
     * @return mixed
     */
    public function deleteData($id)
    {
        $arr = $this->_db->where(['id'=>$id])->delete();
        return $arr;
    }

}