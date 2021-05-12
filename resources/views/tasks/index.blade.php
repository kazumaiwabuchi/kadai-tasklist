@extends('layouts.app')

@section('content')

<!-- ここにページ毎のコンテンツを書く -->
    @if(Auth::check())
        @if (count($tasks) > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>タスク一覧</th>
                        <th>status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                    <tr>
                        {{--タスク詳細ページへのリンク--}}
                        <td>{!! link_to_route('tasks.show', $task->id, ['task' => $task->id]) !!}</td>
                        <td>{{ $task->content }}</td>
                        <td>{{ $task->status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        
        {{-- ページネーションのリンク --}}
        {{ $tasks->links() }}
        {{-- タスク作成ページへのリンク --}}
        {!! link_to_route('tasks.create', '新規タスクの投稿', [], ['class' => 'btn btn-primary']) !!}
        
     @else
        <div class="center jumbotron">
            <div class="text-center">
                <h1>Tasklist</h1>
                {{-- ユーザ登録ページへのリンク --}}
                {!! link_to_route('signup.get', '今すぐ登録する', [], ['class' => 'btn btn-lg btn-primary']) !!}
            </div>
        </div>
    @endif

@endsection