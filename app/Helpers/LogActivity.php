<?php
namespace App\Helpers;
use Illuminate\Http\Request;
use App\Models\LogActivity as LogActivityModel;
use Illuminate\Support\Facades\Auth;
class LogActivity
{
	// $subject,$action,$dateA,$description
	public static function addToLog($action, $desc, Request $request)
	{
		$log = [];
		$log['user_id'] = Auth::user()->id;
		$log['Action'] = $action;
		$log['Description'] = $desc;
		$log['FullUrl'] = $request->fullUrl();
		$log['method'] = $request->method();
		$log['ip'] = $request->ip();
		$log['agent'] = $request->header('user-agent');
		LogActivityModel::create($log);
	}
	public function index()
	{
		$logs = LogActivityModel::orderBy('id', 'DESC')->get();
		return view('Admin.LogActivity', compact('logs'));
	}
	public function TB()
	{
		$logs = LogActivityModel::orderBy('id', 'DESC')->get();
		return view('dashboard', compact('logs'));
	}
}
