<!DOCTYPE html>
<html lang="ja">
  <head>
      <title>Log In</title>
  </head>
  <body>
    <h1>{{{ $page->name }}}の編集</h1>
    <small>最終更新：{{ $page->updated_at->format('Y年m月d日') }}</small>

    <hr>

    {{ Form::model($page, array('route' => array('plugin.edit.post'))) }}
        {{ Form::label('body', '本文') }}<br>
        {{ Form::textarea('body') }}
        <br>
        {{ Form::hidden('name') }}
        {{ Form::hidden('body_version') }}
        {{ Form::submit('保存'); }}
    {{ Form::close() }}
  </body>
</html>
