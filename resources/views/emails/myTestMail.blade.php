<!DOCTYPE html>
<html>
<head>
    <title>Dream Home Seller.com</title>
</head>
<body>


<p><strong>Hi {{$reminder->sales->lead->fullname}},</strong></p>

<p>
    On behalf of Dream Home Seller team, we would like to give you a friendly reminder regarding your payment details indicated below.
</p>
<strong>PROJECT: {{$reminder->sales->project->name}}</strong><br/>
<strong>MODEL UNIT: {{$reminder->sales->modelUnit->name}}</strong><br/>
<strong>PHASE/BLOCK/LOT: Phase: {{$reminder->sales->phase}}, Block: {{$reminder->sales->block}}, Lot: {{$reminder->sales->lot}}</strong><br/>
<strong>FINANCING: {{$reminder->sales->financing}}</strong><br/>
<strong style="color:cornflowerblue;">DUE DATE: {{\Carbon\Carbon::create($reminder->schedule)->format('M-d-Y')}}</strong>
<strong style="color:cornflowerblue;">DUE AMOUNT: PHP {{$reminder->amount}}</strong>

<br/><br/>
<p>
    If you already settled your payment, please, disregard this notice or if you have any concerns you may contact your sales agent on the details below.<br/>
    <strong>SALES AGENT           : {{$reminder->sales->user->fullname}}</strong><br/>
    <strong>SALES AGENT MOBILE NO.: {{$reminder->sales->user->mobileNo}}</strong><br/>
    <strong>SALES AGENT EMAIL     : {{$reminder->sales->user->email}}</strong><br/>
</p>

</body>
</html>
