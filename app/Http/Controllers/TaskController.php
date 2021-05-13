<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;    // 追加

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];//空のデータを作成
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
          // 認証済みユーザのタスク一覧を取得
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(25);
            
            $data=[
                "user" => $user,
                "tasks" => $tasks,
            ];
        }
        

        // タスク一覧ビューでdataを表示,認証済みユーザで無ければ空データが渡される
        return view('tasks.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $task = new Task;

        // タスク作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         // バリデーション
        $request->validate([
            'status' => 'required|max:10',   
            'content' => 'required|max:255',
        ]);
        
        // 認証済みユーザ（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        $request->user()->tasks()->create([
            "status" => $request->status,
            'content' => $request->content,
        ]);
        
        // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        // 認証済みユーザ（閲覧者）がそのタスクの所有者である場合は
        if (\Auth::id() === $task->user_id) {
            // タスク詳細ビューでそれを表示
            return view('tasks.show', [
                'task' => $task,
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
          // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        // タスク編集ビューでそれを表示
        return view('tasks.edit', [
            'task' => $task,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         // バリデーション
        $request->validate([
            'status' => 'required|max:10',   
            'content' => 'required|max:255',
        ]);
         // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        if (\Auth::id() === $tasks->user_id) { // 認証済みユーザ（閲覧者）がそのタスクの所有者である場合は
        // 認証済みユーザ（閲覧者）のタスクとして更新
        $request->user()->tasks()->create([
            "status" => $request->status,
            'content' => $request->content,
        ]);
        }
        
        // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }
        

        // トップページへリダイレクトさせる
        return redirect('/');
    }
}
