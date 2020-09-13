<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;
use app\index\model\grade;
use think\Request;
use think\Page;

class Index extends Controller
{
    public function index()
    {
       /* return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ad_bd568ce7058a1091"></think>';*/
	   return $this->view->fetch('grade/grade1');
    }
	public function select()
    {
		$grade = new grade();
		$pagesize=5;
		$info = $grade->order('id ACS')->paginate($pagesize);
		$page = $info->render();
		//$info = $grade->select();
		$this->assign('info',$info);
		$this->assign('page', $page);
		//$this->view->assign('page',$page);
        return $this->view->fetch('grade/select');
	}
	public function search(Request $request){
		if(request()->isPost()){ 
			//$v = $request->get('v');
			$date = input('post.');
			$v = $date['sv'];
			$grade = new grade();
			$pagesize=5;
			$info = $grade->order('id ACS')->where('name','like', '%'.$v.'%')->paginate($pagesize,false,['query'=>$request->param()]); 
			$page = $info->render();
			$this->assign('info', $info);
			$this->assign('page', $page);
			return $this->fetch('grade/select');
		}else{  //默认打开页面
			$grade = new grade();
			$pagesize=5;
			$info = $grade->order('id ACS')->paginate($pagesize);
			$page = $info->render();
			$this->assign('info',$info);
			$this->assign('page', $page);
			return $this->view->fetch('grade/select');
		}	
	}
	public function add()
    {
		if(request()->isPost()){	
			$date = input('post.');
			// $id = $date['id'];
			$name = $date['name'];
			$content = $date['content'];
			$graduates = $date['graduates'];
			$starttime = $date['starttime'];
			$endtime = $date['endtime'];
			$starttime1 = strtotime($starttime);
			$endtime1 = strtotime($endtime);
			$status = input('status');
			if($status=="正常"){
				$status = 1;
				}else{
					$status = 9;
					}
			// if(empty($id)){
				
			// 	$this->error('ID不能为空');
			
			// 	}
			// if(empty($name)){
				
			// 	$this->error('年级名不能为空');
			
			// 	}
			// if(empty($content)){
				
			// 	$this->error('描述不能为空');
			
			// 	}	
			// if(empty($graduates)){
				
			// 	$this->error('届不能为空');
			
			// 	}
			// if(empty($starttime)){
				
			// 	$this->error('开始时间不能为空');
			
			// 	}
			// if(empty($endtime)){
				
			// 	$this->error('结束时间不能为空');
			
			// 	}
			// if(empty($status)){
				
			// 	$this->error('状态不能为空');
			
			// 	}
				$grade = new grade();
				// $info1 = $grade->where('id',$id)->select();
				// if($info1){
				// 	$this->error('id重复！');
				// }
				$res = $grade->insert(array('name'=>$name,'content'=>$content,'graduates'=>$graduates,'starttime'=>$starttime1,'endtime'=>$endtime1,'status'=>$status));	 
				if($res){	
					$pagesize=5;
					$info = $grade->order('id ACS')->paginate($pagesize);
					$page = $info->render();
					//$info = $grade->select();
					$this->view->assign('info',$info);
					$this->assign('page', $page);
					//$this->success("添加成功！");	 	
					return $this->view->fetch('grade/select');
				}
		}
	}
	public function edit(Request $request){
		$grade = new grade();
		$id = $request->param('id');
		$res = $grade->where('id',$id)->select();
		$this->assign('res', $res);	
		return $this->fetch('grade/edit');
	}
	public function doedit(Request $request)
    {
		if(request()->isPost()){	
			$data = input('post.');
			// $id = $data['id1'];
			$id = $request->param('id');
			$name = $data['name1'];
			$content = $data['content1'];
			$graduates = $data['graduates1'];
			$starttime = $data['starttime1'];
			$endtime = $data['endtime1'];
			$starttime1 = strtotime($starttime);
			$endtime1 = strtotime($endtime);
			$status = input('status1');
			if($status=="正常"){
				$status = 1;
			}else{
					$status = 9;
				}
			$grade = new grade();
			$res = $grade->where('id',$id)->update(array('name'=>$name,'content'=>$content,'graduates'=>$graduates,'starttime'=>$starttime1,'endtime'=>$endtime1,'status'=>$status));
			if($res){	
				$pagesize=5;
				$info = $grade->order('id ACS')->paginate($pagesize,false,['query' => request()->param()]);
				$page = $info->render();
				$this->assign('info',$info);
				$this->assign('page', $page);	 	
				return $this->fetch('grade/select');
			}
		}			
    }
	public function delete(Request $request)
    {
		$grade = new grade();
		$id = $request->param('id');
		$res = $grade->where('id',$id)->delete();
		$pagesize=5;
		$info = $grade->order('id ACS')->paginate($pagesize,false,['query' => request()->param()]);
		$page = $info->render();
		$this->view->assign('info',$info);
		$this->assign('page', $page);
		return $this->view->fetch('grade/select');
    }
}
