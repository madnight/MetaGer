@extends('layouts.subPages')

@section('title', $title )

@section('content')
<h1>Testseite für die Freie Universität Berlin</h1>
<p>Diese Webseite dient der Veranschaulichung einer angepassten und werbefreien Suche, welche für die FU-Berlin verwendet werden könnte.</p>
<p>Über die Suchmaske auf dieser Webseite kann eine angepasste MetaGer-Suche gestartet werden, welche auf der Domain "fu-berlin.de" inklusive aller Subdomains "*.fu-berlin.de" sucht. Ausgeschlossen von der Suche werden hierbei Ergebnisse der Subdomain "userpage.fu-berlin.de".</p>

<form class="metager-searchform" action="" method="get" accept-charset="UTF-8" >
  <style type="text/css">
  	.aufruf-winter {
  	display: none!important;
  }
  </style>
  <style type="text/css" scoped>

  .metager-searchinput {
  height: 30px;
  padding: 6px 12px;
  font-size: 14px;
  line-height: 1.42857;
  color: #555;
  background-color: #FFF;
  background-image: none;
  border: 1px solid #CCC;
  border-right: 0px none;
  border-radius: 4px;
  border-top-right-radius: 0px;
  border-bottom-right-radius: 0px;
  margin:0px;
  }
  .metager-searchbutton {
  height: 30px;
  border-left: 0px none;
  border-top-right-radius: 4px;
  border-bottom-right-radius: 4px;
  border-top-left-radius: 0px;
  border-bottom-left-radius: 0px;
  border: 1px solid #CCC;
  padding: 6px 12px;
  margin:0px;
  font-size: 14px;
  font-weight: normal;
  line-height: 1;
  white-space: nowrap;
  color: #555;
  text-align: center;
  background-color: #EEE;
  }
  .metager-logo {
  height: 30px;
  float: left;
  top:-2px;
  margin-right: 3px;
  }
  </style>
  <a href="https://metager3.de"><img class="metager-logo" title="Sicher suchen & finden mit MetaGer" alt="MetaGer" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAN8AAABGAgMAAAAx/qk0AAAADFBMVEX/wmn/3KL/6Mj+/vtPnQnhAAAC10lEQVRIx+2XMa7TQBCG1zaJkYwUGp5E5RbR+AJI9g2gQLQ0vDpHsMUJKB49DRIKBRdAco6wN8AFB7CQkfzEZod/Zu3ENgSkbRBStnDkt/uNZ/6ZnX2r6KvyGFujyIdTkVG9F6g+q9YPfKL2fuA9VfmBd1XhB95Rl3EZl/Evxur62dm5gAg7uqSxA613pw2+JqI358CQCD2EqBneEzr1ohLg4WyfJKwMT+CGqonNP4NaxfwYwaOrm7+DGzo6mJ5AeLojcw6M2ctMwNU13jM+GYIXzxUHrtWawQc3TsdXUzAhCJqzQkFNnbjXc3AavljMPxJxNdTvtrOvY2UH2wBhwgrYxUzjfThdMn4L6Rv1c/BTF9AXRMbiVykeLUyIjWbMNN5C+oEvzMCXfURbknnS2QgSPrQfVUCRROzKFEzpqUlsTgipj6l9THb3bnODzxf5oC++nNKewdn5ltHGpCZnz3QIm7Xhc4WzUg4nLwwkpGO4e38K5pQc8g5gCn1Yp36sg9qy+50qLbzRydxRgDa2dVseYEHNQVGE/2Ic2CxBrh04CMOgAjG8ZTlQbZhqA5iKqNnQ4iAuD5itANYsHjLTSlFA1QGUUic9KX43ahPyIqNkvgsZLEcw/EhN5MBJDY9ggBVwx4ERQMmoVVxx0QRcFDkjEpnMt1iJ1bcqhzHi5GsH7rPfgW14lDOS3akROoIuuB9ETs6cli0HOdccmQMZ4u6BoEv8TEC7bDmtKiuOjEtmXQxgCDCXXVaJXA/Z919A0YCrc1UDtB+Qa95SGTcAqli7qwP7vugc7eBgLjnAVjCSxs5lE3Hy04hDc7CRNqBda+KUY5eRBegyr8SiUcvmE0t7YzkiSZ6SIqDv7AjX0q1rdr2ixT+biYBSiLW07Yz3A70eKs8W0l7xs9wc08wcD4qV62rq6r2U2eptcTlGL+O/HN4XFu8rkvelzPsa6H3x9L7q+l6ufwK7PWV5kEbECQAAAABJRU5ErkJggg=="></a><input class="metager-searchinput" name="eingabe" placeholder="Suche mit MetaGer..." required value="{{ Request::input('eingabe', '') }}"></input><button class="metager-searchbutton" type="submit">Suchen</button></form>

  @if($link !== "")
  	<br />
  	<p>Der generierte Link lautet wie folgt:</p>
  	<code>https://metager.de{!! $link !!}</code>
  	<p>Wird dieser z.B. in einem IFrame aufgerufen, erhält man eine Ergebnisseite wie am Ende dieser Seite sichtbar.</p>
  	<p>Die wichtigsten Parameter in der Url sind hierbei folgende</p>
  	<ul>
  	<li><code>eingabe={{Request::input('eingabe')}} -host:userpage.fu-berlin.de</code> - Dies ist das Suchwort in Kombination mit der Anweisung alle Links von "userpage.fu-berlin.de" zu entfernen.</li>
  	<li><code>password={{$password}}</code> - Dies ist das generierte Passwort. Es ist abhängig von der Sucheingabe. Ein korrektes Passwort sorgt dafür, dass auf der Ergebnisseite keine Werbung angezeigt wird.</li>
  	<li><code>site=fu-berlin.de</code> - Dies ist die Anweisung, dass die Suche nur für die Seiten der FU-Berlin durchgeführt werden soll.</li>
  	<li><code>quicktips=off</code> - Dies ist die Anweisung, dass die Quicktips, welche in der normalen MetaGer-Suche bei großen Bildschirmen am rechten Rand erscheint, entfernt werden sollen.</li>
  	<li><code>encoding=utf8</code> - Dieser Parameter darf nicht fehlen, falls die Sucheingaben in UTF-8 übertragen werden. Ansonsten kann es passieren, dass z.B. Umlaute nicht korrekt übertragen und verarbeitet werden. Fehlt dieser Parameter, so wird eine Encoding in "ISO-8859-1" vermutet.
  	</ul>
  	<iframe src="{!!$link!!}" style="width: 100%; border: 0; margin-top: 25px;height: 200vh; overflow:scroll;"></iframe>
  @endif

@endsection
