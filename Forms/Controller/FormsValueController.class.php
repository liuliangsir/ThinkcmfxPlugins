<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2015 http://www.5iymt.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 5iymt <1145769693@qq.com>
// +----------------------------------------------------------------------
// | Version: 2015-5-23 12:15:11
// +----------------------------------------------------------------------

namespace plugins\Forms\Controller; 
use Api\Controller\PluginController;

class FormsValueController extends PluginController{
	
	function _initialize(){
		$adminid=sp_get_current_admin_id();//获取后台管理员id，可判断是否登录
		if(!empty($adminid)){
			$this->assign("adminid",$adminid);
		}else{
			//TODO no login
		}
		$forms_id=I('forms_id',0,'trim');
		$forms_data=D("plugins://Forms/PluginForms")->field('id,title')->find($forms_id);
		$this->assign('forms_data',$forms_data);
		$this->assign('forms_url',sp_plugin_url('Forms://FormsValue/index',array('forms_id'=>$forms_id)));
		$this->model=D("plugins://Forms/PluginFormsValue");
		$this->Attribute_model=D("plugins://Forms/PluginFormsAttribute");
	}
	//表单数据，一般为用户提交数据
	function index(){
		$forms_id = I("get.forms_id",0,'intval');
		$list=$this->model->where(array('forms_id'=>$forms_id))->select();
		$this->assign('list',$list);		
		$this->display(":admin_value");
	}
	
	//修改表单
	public function edit(){
		if (IS_POST) {
			
			if ($this->model->create()) {
				if ($this->model->save()!==false) {
					$this->success("保存成功！");
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->model->getError());
			}
		}else{
			$id=I("get.id");
			$form=$this->model->where("id=$id")->find();
			$this->assign($form);
			$this->display(":admin_edit");			
		}
	}
	//删除表单
	public function del(){
		$id = intval(I("get.id"));//删除表单会同时删除用户提交的和表单的字段		
		if ($this->model->delete($id)!==false) {
			$this->success("删除成功！");
		} else {
			$this->error("删除失败！");
		}			
	}
}