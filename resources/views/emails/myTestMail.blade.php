<!DOCTYPE html>
<html>
<head>
    <title>Dream Home Seller.com</title>
</head>
<body>

<p>
    Hi {{$reminder->sales->lead->fullname}},<br/>

    This is to remind you regarding your monthly payment amounting {{$reminder->amount}}
    on {{\Carbon\Carbon::create($reminder->schedule)->format('Y-M-d')}} for the project you reserved at {{$reminder->sales->lead->project->name}}, model unit {{$reminder->sales->modelUnit->name}}
</p>
</body>
</html>
