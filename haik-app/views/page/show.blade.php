<!DOCTYPE html>
<html lang="ja">
  <head>
      <title>{{{ $title or $page }}}</title>
  </head>
  <body>
    {{ $content }}

    <hr>
    <small>最終更新：{{ $updated_at }}</small>
    {{ link_to_route('plugin.edit', $page.'の編集', array($page)) }}

  </body>
</html>
