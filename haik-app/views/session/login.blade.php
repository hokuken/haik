<!DOCTYPE html>
<html lang="ja">
  <head>
      <title>Log In</title>
  </head>
  <body>
        {{ Form::open() }}
            @if ($error = $errors->first('password'))
            <div style="color:red;">
                {{ $error }}
            </div>
            @endif
            {{ Form::label('email', 'Eメールアドレス：') }}
            {{ Form::text('email', Input::old('email', '')) }}
            <br>
            {{ Form::label('password', 'パスワード：') }}
            {{ Form::password('password') }}
            <br>
            {{ Form::submit('ログイン'); }}
        {{ Form::close() }}
  </body>
</html>
