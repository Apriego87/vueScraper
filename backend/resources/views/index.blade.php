<html>

<body>
    <h1>Noticias</h1>

    @foreach ($items as $item)
        <a href="{{ $item['link'] }}">{{ $item['title'] }}</a><br>
    @endforeach

</body>

</html>
