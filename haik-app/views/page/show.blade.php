<!DOCTYPE html>
<html lang="ja">
  <head>
      <title>{{{ $page->name }}}</title>
  </head>
  <body>
    {{ $page->getContent() }}

    <hr>
    <small>最終更新：{{ $page->updated_at->format('Y年m月d日') }}</small>
    {{ link_to_route('plugin.edit', $page->name.'の編集', array($page->name)) }}

  </body>
</html>
