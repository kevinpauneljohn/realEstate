<!DOCTYPE html>
<html>
<head>
    <title>Dream Home Seller.com</title>
</head>
<body>

<p>
    Hi {{$reminder->sales->lead->fullname}},<br/>

    This is to remind you regarding your monthly payment amounting {{$reminder->amount}}
    on {{$reminder->schedule}} for the you reserved at {{$reminder->sales->lead->project}}, model unit {{$reminder->sales->modelUnit->name}}
</p>
</body>
</html>
