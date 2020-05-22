<form action="{{route('test')}}" method="post">
    @csrf
    <input type="text" name="test">
    <input type="submit" value="submit">
</form>
